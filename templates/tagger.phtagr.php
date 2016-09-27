
<!-- Funktion: Klick auf ein Tag/Category fügt diese dem Bild hinzu, und löscht diese aus der Liste der verfügbaren.
     Ein Button Weiter/Zurück holt das nächste Bild lt. Filter aus dem Bestand.
     Wird auf ein Tag/Category des Bildes (Anzeige unterhalb des Bildes) wird dieser Tag/Category wieder entfernt (und der Liste verfügbaren hinzugefügt)
     Filter wird mit jedem Bildwechsel aktualisiert!
-->

    <div class="row">
      <div class="container-fluid">
        <div class="col-xs-12 col-sm-2 col-md-2">
          <div id="Categorie" class="border-box">
            <h4 id="Categorie.Topic" class="rechtsbuendig">Kategorie</h4>
            <ul id="Categorie.List" class="phtagr.category.list rechtsbuendig">
              <li>Beispieleintrag</li>
            </ul>
          </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8">
          <div id="Picture" class="border-box">
            <h4 id="Picture.Topic" class="zentriert">Thumbnail label</h4>
            <img id="Picture.Image" class="zentriert" src="img/dummy_picture.svg" alt="" width="640px">
            <div id="Picture.Desc" class="caption">
              <p class="zentriert">Zeitstempel: xx.xx.xxxx xx:xx:xx</br>
              Schlüsselwörter: .., .., ..</br>
              Kategorieen: .., ..., ..</p>
            </div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2">
          <div id="Tag" class="border-box">
            <h4 id="Tag.Topic">Schl&uuml;sselw&ouml;rter</h4>
            <ul id="Tag.List" class="phtagr.tag.list">
              <li>Beispieleintrag</li>
            </ul>
          </div>
        </div>

      </div>
    </div>
