<?php
/*
 * phtagr.
 * 
 * Multi-user image gallery.
 * 
 * Copyright (C) 2006-2009 Sebastian Felis, sebastian@phtagr.org
 * 
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; version 2 of the 
 * License.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

App::import('Core', array('Validation'));
App::import('File', 'Search', array('file' => APP.'search.php'));

class SearchComponent extends Search
{
  var $components = array('QueryBuilder');

  var $controller = null;

  var $Validation = null;

  /** Parameter validation array
    @see http://book.cakephp.org/view/125/Data-Validation */
  var $validate = array(
    'categories' => array('rule' => array('maxLength', 30)),
    'categoryOp' => array('rule' => array('inList', array('AND', 'OR'))),
    'east' => 'decimal',
    'file' => 'alphaNumeric',
    'from' => array('rule' => array('custom', '/^\d{4}-\d{2}-\d{2}([ T]\d{2}:\d{2}\d{2})?$/')),
    'groups' => 'alphaNumeric',
    'media' => 'numeric',
    'north' => 'decimal',
    'locations' => array('rule' => array('maxLength', 30)),
    'locationOp' => array('rule' => array('inList', array('AND', 'OR'))),
    'operand' => array('rule' => array('inList', array('AND', 'OR'))),
    'page' => array('numericRule' => 'numeric', 'minRule' => array('rule' => array('comparison', '>=', 1))),
    'pos' => array('numericRule' => 'numeric', 'minRule' => array('rule' => array('comparison', '>=', 1))),
    'show' => array('numericRule' => 'numeric', 'minRule' => array('rule' => array('comparison', '>=', 4)), 'maxRule' => array('rule' => array('comparison', '<=', 240))),
    'sort' => array('rule' => array('inList', array('date', '-date', 'newest', 'changes', 'viewed', 'popularity', 'random'))),
    'tags' => array('rule' => array('maxLength', 30)),
    'tagOp' => array('rule' => array('inList', array('AND', 'OR'))),
    'south' => 'decimal',
    'to' => array('rule' => array('custom', '/^\d{4}-\d{2}-\d{2}([ T]\d{2}:\d{2}\d{2})?$/')),
    'user' => 'alphaNumeric',
    'users' => array('rule' => array('custom', '/^-?[\w\d]+$/')),
    'west' => 'decimal',
    'visibility' => array('rule' => array('inList', array('private', 'group', 'user', 'public'))),
    );

  /** Array of disabled parameter names */
  var $disabled = array();

  /** Base URL for search helper */
  var $baseUri = '/explorer/query';

  /** Default values */
  var $defaults = array(
    'page' => '1',
    'pos' => false,
    'show' => '12',
    'sort' => 'default',
    );

  function initialize(&$controller) {
    $this->controller = &$controller;
    $this->Validation =& Validation::getInstance();
    $this->clear();
  }

  function clear() {
    $this->_data = $this->defaults;
  }

  /** Validates the parameter value
    @param name Parameter name
    @param value Parameter value
    @result True on success validation */
  function validate($name, $value) {
    if (!isset($this->validate[$name])) { 
      // check for parameter without validation
      $key = array_search($name, $this->validate); 
      if ($key !== false && is_numeric($key)) {
        if (in_array($name, $this->disabled)) {
          Logger::verbose("Parameter '$name' is disabled");
          return false;
        }
        Logger::verbose("Parameter '$name' has no validation!");
        return true;
      }
      Logger::verbose("Parameter '$name' does not exists");
      return false;
    } 
    if (in_array($name, $this->disabled)) {
      Logger::verbose("Parameter '$name' is disabled");
      return false;
    }

    $ruleSet = $this->validate[$name];
    $result = false;
    if (!is_array($ruleSet) || isset($ruleSet['rule'])) {
      $result = $this->_dispatchRule($ruleSet, $value);
    } else {
      $result = true;
      foreach ($ruleSet as $name => $rule) {
        $result &= $this->_dispatchRule($rule, $value);
        if (!$result) {
          Logger::verbose("Failed multiple rules on rule '$name': $value");
        }
      }
    }
    return $result;
  }

  /** Dispatch the rule 
    @param ruleSet Rule name or single rule array
    @param check Value to check
    @result True on successful validation */
  function _dispatchRule($ruleSet, $check) {
    if (!is_array($ruleSet)) {
      $rule = $ruleSet;
      $params = array($check);
    } elseif (!isset($ruleSet['rule'])) {
      Logger::err("Rule definition is missing");
      return false;
    } elseif (is_array($ruleSet['rule'])) {
      $rule = $ruleSet['rule'][0];
      $params = $ruleSet['rule'];
      $params[0] = $check;
    } else {
      $rule = $ruleSet['rule'];
      $params = array($check);
    }

    if (method_exists(&$this, $rule)) {
      $result = $this->dispatchMethod($rule, $params);
    } elseif (method_exists(&$this->Validation, $rule)) {
      $result = $this->Validation->dispatchMethod($rule, $params);
    } else {
      Logger::debug("Rule '$rule' could not be found");
      return false;
    }

    if (!$result) {
      Logger::verbose("Failed rule '$rule': $check");
      if (isset($ruleSet['message'])) {
        Logger::debug($ruleSet['message']);
      }
    }
    return $result;
  }

  /** Set the disabled search fields according to the user role */
  function _setDisabledFields() {
    // disable search parameter after role
    $role = $this->controller->getUserRole();
    $disabled = array();
    switch ($role) {
      case ROLE_NOBODY:
        $disabled[] = 'file';
      case ROLE_GUEST:
        $disabled[] = 'groups';
        $disabled[] = 'visibility';
      case ROLE_USER:
      case ROLE_SYSOP:
      case ROLE_ADMIN:
        break;
      default:
        Logger::err("Unhandled role $role");
    }
    $this->disabled = $disabled;
  }

  /** parse all parameters given in the URL and adds them to the search */
  function parseArgs() {
    $this->_setDisabledFields();
    foreach($this->controller->passedArgs as $name => $value) {
      if (is_numeric($name) || empty($value)) {
        continue;
      }
      $singles = array('pos'); // quick fix
      if (!in_array($name, $singles) && $name == Inflector::pluralize($name)) {
        $values = explode(',', $value);
        $this->addParam($name, $values);
      } else {
        $this->setParam($name, $value);
      }
    }
  }

  function paginate() {
    $query = $this->QueryBuilder->build($this->getParams()); 
    $tmp = $query;
    unset($query['limit']);
    unset($query['page']);
    $count = $this->controller->Media->find('count', $query);
    $query = $tmp;

    $params = array(
      'pageCount' => 0, 
      'current' => 0, 
      'nextPage' => false, 
      'prevPage' => false,
      'baseUri' => $this->baseUri,
      'defaults' => $this->defaults,
      'data' => $this->getParams()
      );

    if ($count == 0) {
      $this->controller->params['search'] = $params;
      return array();
    }
    $params['pageCount'] = ceil($count / $this->getShow(12));
    if ($this->getPage() > $params['pageCount']) {
      $this->setPage($params['pageCount']);
      $params['data'] = $this->getParams();
      $query['page'] = $params['pageCount'];
    }
    if ($this->getPage() > 1) {
      $params['prevPage'] = true;
    }
    if ($this->getPage() < $params['pageCount']) {
      $params['nextPage'] = true;
    }
    $params['page'] = $this->getPage();

    // get all media and set access flags
    $data = $this->controller->Media->find('all', $query);
    $user = $this->controller->getUser();
    for ($i = 0; $i < count($data); $i++) {
      $this->controller->Media->setAccessFlags(&$data[$i], $user);
    }
    
    if ($this->getUser(false) != false) {
      $username = $this->getUser();
      $params['baseUri'] = '/explorer/user/'.$username;
      $params['defaults']['user'] = $username;
    }

    // Set data for search helper
    $this->controller->params['search'] = $params;

    return $data;
  }

  function paginateMedia($id) {
    $query = $this->QueryBuilder->build($this->getParams()); 
    unset($query['limit']);
    unset($query['offset']);
    $count = $this->controller->Media->find('count', $query);

    $params = array(
      'pos' => 0, 
      'current' => false,
      'prevMedia' => false,
      'nextMedia' => false, 
      'baseUri' => $this->baseUri,
      'defaults' => $this->defaults,
      'data' => $this->getParams()
      );

    $data = $this->controller->Media->findById($id);
    if ($count == 0 || !$data) {
      $this->controller->params['search'] = $params;
      return array();
    }
    $this->controller->Media->setAccessFlags(&$data, $this->controller->getUser());

    $pos = $this->getPos();
    $mediaOffset = 1; // offset from previews media
    $show = 3; // show size
    if ($count > 2) {
      if ($count <= $pos) {
        // last media [current, next]
        $this->setPos($count);
        $params['data'] = $this->getParams();
        $pos = $count - 1;
        $show = 2;
      } elseif ($pos == 1) {
        // first media [prev, current]
        $show = 2;
        $mediaOffset = 0;
      } else {
        // [prev, current, next]
        $pos--;
      }
    } elseif ($count == 2) {
      $show = 2;
      if ($pos == 1) {
        // [current, next]
        $mediaOffset = 0;
      } // else [prev, current]
    } else {
      // [current]
      $show = 1;
      $mediaOffset = 0;
    }

    // get neigbors
    $query['fields'] = 'Media.id';
    $query['offset'] = $pos - 1;
    $query['limit'] = $show;
    if ($show > 1) {
      $result = $this->controller->Media->find('all', $query);
      $ids = Set::extract('/Media/id', $result);
      if ($mediaOffset == 1) {
        $params['prevMedia'] = $ids[0];
      } 
      if (isset($ids[$mediaOffset + 1])) {
        $params['nextMedia'] = $ids[$mediaOffset + 1];
      } 
    }

    $params['current'] = $id;
    if ($this->getUser(false) != false) {
      $username = $this->getUser();
      $params['baseUri'] = '/explorer/user/'.$username;
      $params['defaults']['user'] = $username;
    }

    // Set data for search helper
    $this->controller->params['search'] = $params;
 
    return $data;
  }
}
?>
