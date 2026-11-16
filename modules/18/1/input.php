<div class="row">
	<div class="col-xs-12">
		<p><b>Strukturierte Daten (schema.org JSON-LD)</b> &ndash; wird im Body der Seite ausgegeben. Nur ausgef&uuml;llte Felder werden &uuml;bernommen. Ohne @type und Name erfolgt keine Ausgabe.</p>
		<div class="alert alert-warning">
			<b>Wichtig (Google-Richtlinie):</b> Strukturierte Daten d&uuml;rfen nur Inhalte auszeichnen, die auch f&uuml;r den Besucher <b>sichtbar auf dieser Seite</b> stehen. Keine versteckten, abweichenden oder erfundenen Angaben &ndash; sonst droht eine manuelle Ma&szlig;nahme / Abwertung durch Google. Die hier eingetragenen Werte (Name, Adresse, Preis, &Ouml;ffnungszeiten usw.) also auch im normalen Seiteninhalt darstellen.
		</div>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">schema.org @type</div>
	<div class="col-xs-8">
		<?php
		$sd_type = 'REX_VALUE[1]'; /** @phpstan-ignore-line */
		$sd_type_options = [
			'' => '— bitte wählen —',
			'LocalBusiness' => 'Lokales Unternehmen (LocalBusiness)',
			'Organization' => 'Organisation / Firma (Organization)',
			'LodgingBusiness' => 'Beherbergungsbetrieb (LodgingBusiness)',
			'VacationRental' => 'Ferienwohnung / Ferienhaus (VacationRental)',
			'Hotel' => 'Hotel (Hotel)',
			'BedAndBreakfast' => 'Pension / B&B (BedAndBreakfast)',
			'Apartment' => 'Apartment (Apartment)',
			'House' => 'Ferienhaus / Haus (House)',
			'Room' => 'Zimmer (Room)',
			'Suite' => 'Suite (Suite)',
			'Resort' => 'Resort (Resort)',
			'Campground' => 'Campingplatz (Campground)',
			'Restaurant' => 'Restaurant (Restaurant)',
			'Store' => 'Geschäft / Laden (Store)',
			'ProfessionalService' => 'Dienstleister (ProfessionalService)',
			'MedicalBusiness' => 'Arzt / Praxis (MedicalBusiness)',
			'Event' => 'Veranstaltung (Event)',
			'Product' => 'Produkt (Product)',
			'Service' => 'Dienstleistung (Service)',
			'FAQPage' => 'FAQ-Seite (FAQPage)',
		];
		echo '<select name="REX_INPUT_VALUE[1]" class="form-control">';
		foreach ($sd_type_options as $sd_type_value => $sd_type_label) {
			echo '<option value="'. htmlspecialchars((string) $sd_type_value, ENT_QUOTES, 'UTF-8') .'"'. ($sd_type_value === $sd_type ? ' selected="selected"' : '') .'>'. htmlspecialchars($sd_type_label, ENT_QUOTES, 'UTF-8') .'</option>';
		}
		echo '</select>';
		?>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Name</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[2]" value="REX_VALUE[2]" class="form-control"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Beschreibung</div>
	<div class="col-xs-8"><textarea name="REX_INPUT_VALUE[3]" rows="3" class="form-control">REX_VALUE[3]</textarea></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">URL der Seite</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[4]" value="REX_VALUE[4]" class="form-control" placeholder="leer lassen = aktuelle Seite (empfohlen)"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Bild</div>
	<div class="col-xs-8">
		REX_MEDIA[id="1" types="jpg,jpeg,png,webp,gif" widget="1"]
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Telefon</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[6]" value="REX_VALUE[6]" class="form-control"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">E-Mail</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[7]" value="REX_VALUE[7]" class="form-control"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Preisspanne (priceRange)</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[8]" value="REX_VALUE[8]" class="form-control" placeholder="z. B. &euro;&euro; oder 80-150 EUR"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Stra&szlig;e und Hausnummer</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[9]" value="REX_VALUE[9]" class="form-control"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">PLZ und Ort</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[10]" value="REX_VALUE[10]" class="form-control" placeholder="z. B. 80333 M&uuml;nchen"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">L&auml;nderkennung</div>
	<div class="col-xs-8"><input type="text" name="REX_INPUT_VALUE[11]" value="REX_VALUE[11]" class="form-control" placeholder="z. B. DE, CH, AT"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Breitengrad (latitude)</div>
	<div class="col-xs-8"><input type="text" id="sd-lat" name="REX_INPUT_VALUE[12]" value="REX_VALUE[12]" class="form-control" placeholder="z. B. 48.1371"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">L&auml;ngengrad (longitude)</div>
	<div class="col-xs-8"><input type="text" id="sd-lng" name="REX_INPUT_VALUE[13]" value="REX_VALUE[13]" class="form-control" placeholder="z. B. 11.5754"></div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4"></div>
	<div class="col-xs-8">
		<button type="button" class="btn btn-default" id="sd-geocode">Koordinaten aus Adresse ermitteln</button>
		<a href="#" id="sd-geocode-check" target="_blank" rel="noopener" style="display:none; margin-left:8px;">auf Karte pr&uuml;fen</a>
		<div id="sd-geocode-msg" style="margin-top:4px;"></div>
		<small>Ermittelt L&auml;ngen- und Breitengrad aus Stra&szlig;e, PLZ/Ort und Land &uuml;ber OpenStreetMap (Nominatim).</small>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">&Ouml;ffnungszeiten</div>
	<div class="col-xs-8">
		<textarea name="REX_INPUT_VALUE[14]" rows="3" class="form-control" placeholder="eine Regel pro Zeile, z. B. Mo-Fr 09:00-18:00">REX_VALUE[14]</textarea>
	</div>
</div>
<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-12"><p><b>Typ-spezifische Angaben</b> &ndash; es werden nur die Felder angezeigt, die zum oben gew&auml;hlten Typ passen. Leere Felder werden ignoriert.</p></div>
</div>

<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Suite">
	<div class="col-xs-4">Anzahl Zimmer</div>
	<div class="col-xs-8"><input type="number" min="0" class="form-control sd-cf" data-sd-kind="number" data-sd-prop="numberOfRooms"></div>
</div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Suite"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Apartment,House,Room,Suite">
	<div class="col-xs-4">Max. G&auml;ste</div>
	<div class="col-xs-8"><input type="number" min="0" class="form-control sd-cf" data-sd-kind="occupancyMax"></div>
</div>
<div class="row" data-sd-row data-sd-types="Apartment,House,Room,Suite"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground">
	<div class="col-xs-4">Check-in Zeit</div>
	<div class="col-xs-8"><input type="time" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="checkinTime"></div>
</div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground">
	<div class="col-xs-4">Check-out Zeit</div>
	<div class="col-xs-8"><input type="time" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="checkoutTime"></div>
</div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Hotel,Resort,BedAndBreakfast,LodgingBusiness">
	<div class="col-xs-4">Sterne (1&ndash;5)</div>
	<div class="col-xs-8"><input type="number" min="1" max="5" step="1" class="form-control sd-cf" data-sd-kind="starRating"></div>
</div>
<div class="row" data-sd-row data-sd-types="Hotel,Resort,BedAndBreakfast,LodgingBusiness"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite">
	<div class="col-xs-4">Haustiere erlaubt</div>
	<div class="col-xs-8"><label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="bool" data-sd-prop="petsAllowed" value="true"><span class="sd-slider"></span></span></label></div>
</div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite,Restaurant">
	<div class="col-xs-4">Ausstattung</div>
	<div class="col-xs-8">
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="WLAN"><span class="sd-slider"></span></span> WLAN</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Parkplatz"><span class="sd-slider"></span></span> Parkplatz</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Pool"><span class="sd-slider"></span></span> Pool</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Klimaanlage"><span class="sd-slider"></span></span> Klimaanlage</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="K&uuml;che"><span class="sd-slider"></span></span> K&uuml;che</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Waschmaschine"><span class="sd-slider"></span></span> Waschmaschine</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Balkon/Terrasse"><span class="sd-slider"></span></span> Balkon/Terrasse</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Nichtraucher"><span class="sd-slider"></span></span> Nichtraucher</label>
		<label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="amenity" value="Barrierefrei"><span class="sd-slider"></span></span> Barrierefrei</label>
	</div>
</div>
<div class="row" data-sd-row data-sd-types="LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite,Restaurant"><div class="col-xs-12">&nbsp;</div></div>

<div class="row" data-sd-row data-sd-types="Restaurant">
	<div class="col-xs-4">K&uuml;che / Gastronomie</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="servesCuisine" placeholder="z. B. Italienisch"></div>
</div>
<div class="row" data-sd-row data-sd-types="Restaurant"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Restaurant">
	<div class="col-xs-4">Speisekarte-URL</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="menu" placeholder="https://..."></div>
</div>
<div class="row" data-sd-row data-sd-types="Restaurant"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Restaurant">
	<div class="col-xs-4">Reservierung m&ouml;glich</div>
	<div class="col-xs-8"><label class="sd-switch-row"><span class="sd-switch"><input type="checkbox" class="sd-cf" data-sd-kind="bool" data-sd-prop="acceptsReservations" value="true"><span class="sd-slider"></span></span></label></div>
</div>
<div class="row" data-sd-row data-sd-types="Restaurant"><div class="col-xs-12">&nbsp;</div></div>

<div class="row" data-sd-row data-sd-types="Event">
	<div class="col-xs-4">Beginn</div>
	<div class="col-xs-8"><input type="datetime-local" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="startDate"></div>
</div>
<div class="row" data-sd-row data-sd-types="Event"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Event">
	<div class="col-xs-4">Ende</div>
	<div class="col-xs-8"><input type="datetime-local" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="endDate"></div>
</div>
<div class="row" data-sd-row data-sd-types="Event"><div class="col-xs-12">&nbsp;</div></div>

<div class="row" data-sd-row data-sd-types="Product">
	<div class="col-xs-4">Marke</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="brand"></div>
</div>
<div class="row" data-sd-row data-sd-types="Product"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Product">
	<div class="col-xs-4">Artikelnummer (SKU)</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="sku"></div>
</div>
<div class="row" data-sd-row data-sd-types="Product"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Product">
	<div class="col-xs-4">Preis</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="offerPrice" placeholder="z. B. 49.90"></div>
</div>
<div class="row" data-sd-row data-sd-types="Product"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="Product">
	<div class="col-xs-4">W&auml;hrung</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="offerCurrency" placeholder="EUR"></div>
</div>
<div class="row" data-sd-row data-sd-types="Product"><div class="col-xs-12">&nbsp;</div></div>

<div class="row" data-sd-row data-sd-types="LocalBusiness,Organization,ProfessionalService,MedicalBusiness,Service">
	<div class="col-xs-4">Einzugsgebiet</div>
	<div class="col-xs-8"><input type="text" class="form-control sd-cf" data-sd-kind="text" data-sd-prop="areaServed" placeholder="z. B. M&uuml;nchen und Umgebung"></div>
</div>
<div class="row" data-sd-row data-sd-types="LocalBusiness,Organization,ProfessionalService,MedicalBusiness,Service"><div class="col-xs-12">&nbsp;</div></div>
<div class="row" data-sd-row data-sd-types="LocalBusiness,Organization,ProfessionalService,MedicalBusiness,Service,LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite,Restaurant,Event,Product">
	<div class="col-xs-4">Profil-/Social-URLs</div>
	<div class="col-xs-8"><textarea rows="2" class="form-control sd-cf" data-sd-kind="sameAs" placeholder="eine URL pro Zeile (Facebook, Instagram, ...)"></textarea></div>
</div>
<div class="row" data-sd-row data-sd-types="LocalBusiness,Organization,ProfessionalService,MedicalBusiness,Service,LodgingBusiness,VacationRental,Hotel,BedAndBreakfast,Resort,Campground,Apartment,House,Room,Suite,Restaurant,Event,Product"><div class="col-xs-12">&nbsp;</div></div>

<input type="hidden" name="REX_INPUT_VALUE[16]" id="sd-extra-json" value="REX_VALUE[16]">

<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Vorschau (JSON-LD)</div>
	<div class="col-xs-8"><pre id="sd-preview" style="max-height:320px; overflow:auto; background:rgba(127,127,127,.1); color:inherit; padding:8px; border:1px solid rgba(127,127,127,.35); border-radius:4px; white-space:pre-wrap; word-break:break-word;">&hellip;</pre><small>So werden die eingegebenen Daten im Frontend als JSON-LD ausgegeben.</small></div>
</div>

<div class="row"><div class="col-xs-12">&nbsp;</div></div>
<div class="row">
	<div class="col-xs-4">Zus&auml;tzliche Eigenschaften (JSON, Fortgeschritten)</div>
	<div class="col-xs-8">
		<textarea name="REX_INPUT_VALUE[15]" rows="5" class="form-control" placeholder='{"numberOfRooms": 3, "amenityFeature": [...]}'>REX_VALUE[15]</textarea>
		<small>Optional, nur f&uuml;r Fortgeschrittene. G&uuml;ltiges JSON-Objekt; wird mit allen obigen Feldern zusammengef&uuml;hrt (kann sie &uuml;berschreiben).</small>
	</div>
</div>

<style>
.sd-switch-row { display: block; margin: 0 0 6px 0; font-weight: normal; cursor: pointer; }
.sd-switch { position: relative; display: inline-block; width: 44px; height: 24px; vertical-align: middle; margin: 0 8px 0 0; }
.sd-switch input { position: absolute; opacity: 0; width: 0; height: 0; }
.sd-switch .sd-slider { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: #ccc; border-radius: 24px; transition: .2s; }
.sd-switch .sd-slider:before { content: ""; position: absolute; width: 18px; height: 18px; left: 3px; top: 3px; background: #fff; border-radius: 50%; transition: .2s; }
.sd-switch input:checked + .sd-slider { background: #4c9f50; }
.sd-switch input:checked + .sd-slider:before { transform: translateX(20px); }
</style>
<script>
(function () {
	var typeSel = document.querySelector('select[name="REX_INPUT_VALUE[1]"]');
	var hidden = document.getElementById('sd-extra-json');
	if (!hidden) { return; }
	var rows = Array.prototype.slice.call(document.querySelectorAll('[data-sd-row]'));
	var fields = Array.prototype.slice.call(document.querySelectorAll('.sd-cf'));

	function currentType() { return typeSel ? typeSel.value : ''; }
	function rowApplies(row) {
		var t = row.getAttribute('data-sd-types');
		if (!t) { return true; }
		return t.split(',').map(function (s) { return s.trim(); }).indexOf(currentType()) >= 0;
	}
	function toggleRows() {
		rows.forEach(function (r) { r.style.display = rowApplies(r) ? '' : 'none'; });
	}
	function fieldVisible(f) {
		var row = f.closest ? f.closest('[data-sd-row]') : null;
		return !row || rowApplies(row);
	}
	function getCuratedData() {
		var data = {};
		var amenities = [];
		var offer = {};
		fields.forEach(function (f) {
			if (!fieldVisible(f)) { return; }
			var kind = f.getAttribute('data-sd-kind');
			var prop = f.getAttribute('data-sd-prop');
			if (kind === 'amenity') { if (f.checked) { amenities.push(f.value); } return; }
			if (kind === 'bool') { if (f.checked) { data[prop] = true; } return; }
			var val = f.value;
			if (val === '' || val === null) { return; }
			if (kind === 'number') { var n = Number(val); if (!isNaN(n)) { data[prop] = n; } return; }
			if (kind === 'occupancyMax') { var m = Number(val); if (!isNaN(m)) { data['occupancy'] = { '@type': 'QuantitativeValue', 'maxValue': m }; } return; }
			if (kind === 'starRating') { var s = Number(val); if (!isNaN(s)) { data['starRating'] = { '@type': 'Rating', 'ratingValue': s }; } return; }
			if (kind === 'offerPrice') { offer.price = String(val); return; }
			if (kind === 'offerCurrency') { offer.priceCurrency = String(val); return; }
			if (kind === 'sameAs') {
				var arr = val.split(/\r\n|\r|\n/).map(function (x) { return x.trim(); }).filter(Boolean);
				if (arr.length) { data['sameAs'] = arr; }
				return;
			}
			data[prop] = val;
		});
		if (amenities.length) {
			data['amenityFeature'] = amenities.map(function (a) {
				return { '@type': 'LocationFeatureSpecification', 'name': a, 'value': true };
			});
		}
		if (offer.price) { offer['@type'] = 'Offer'; if (!offer.priceCurrency) { offer.priceCurrency = 'EUR'; } data['offers'] = offer; }
		return data;
	}
	function serialize() {
		var curated = getCuratedData();
		hidden.value = Object.keys(curated).length ? JSON.stringify(curated) : '';
		buildPreview();
	}
	function baseVal(n) {
		var el = document.querySelector('[name="REX_INPUT_VALUE[' + n + ']"]');
		return el ? el.value.trim() : '';
	}
	function buildPreview() {
		var pre = document.getElementById('sd-preview');
		if (!pre) { return; }
		var type = currentType();
		var name = baseVal(2);
		if (!type || !name) {
			pre.textContent = '(Es wird nichts ausgegeben, bis Typ und Name gefüllt sind.)';
			return;
		}
		var data = { '@context': 'https://schema.org', '@type': type, 'name': name };
		var desc = baseVal(3); if (desc) { data.description = desc; }
		var url = baseVal(4); data.url = url !== '' ? url : '(aktuelle Seite)';
		var mediaEl = document.querySelector('[name="REX_INPUT_MEDIA[1]"]');
		var mediaFile = mediaEl ? mediaEl.value.trim() : '';
		if (mediaFile) { data.image = '/media/' + mediaFile; }
		var tel = baseVal(6); if (tel) { data.telephone = tel; }
		var email = baseVal(7); if (email) { data.email = email; }
		var price = baseVal(8); if (price) { data.priceRange = price; }
		var street = baseVal(9), zipCity = baseVal(10), country = baseVal(11);
		var addr = { '@type': 'PostalAddress' };
		if (street) { addr.streetAddress = street; }
		if (zipCity) {
			var m = zipCity.match(/^\s*(\S+)\s+(.+)$/);
			if (m) { addr.postalCode = m[1]; addr.addressLocality = m[2].trim(); } else { addr.addressLocality = zipCity; }
		}
		if (country) { addr.addressCountry = country; }
		if (Object.keys(addr).length > 1) { data.address = addr; }
		var lat = parseFloat(baseVal(12).replace(',', '.'));
		var lng = parseFloat(baseVal(13).replace(',', '.'));
		if (lat && lng) { data.geo = { '@type': 'GeoCoordinates', 'latitude': lat, 'longitude': lng }; }
		var hours = baseVal(14);
		if (hours) {
			var hl = hours.split(/\r\n|\r|\n/).map(function (x) { return x.trim(); }).filter(Boolean);
			if (hl.length) { data.openingHours = hl.length === 1 ? hl[0] : hl; }
		}
		var curated = getCuratedData();
		Object.keys(curated).forEach(function (k) { data[k] = curated[k]; });
		var adv = baseVal(15);
		if (adv) { try { var a = JSON.parse(adv); if (a && typeof a === 'object') { Object.keys(a).forEach(function (k) { data[k] = a[k]; }); } } catch (e) { /* invalid JSON: ignore in preview */ } }
		pre.textContent = JSON.stringify(data, null, 2);
	}
	function populate() {
		var raw = (hidden.value || '').trim();
		if (!raw) { return; }
		var data;
		try { data = JSON.parse(raw); } catch (e) { return; }
		fields.forEach(function (f) {
			var kind = f.getAttribute('data-sd-kind');
			var prop = f.getAttribute('data-sd-prop');
			if (kind === 'amenity') {
				var names = (data.amenityFeature || []).map(function (a) { return a && a.name; });
				f.checked = names.indexOf(f.value) >= 0;
				return;
			}
			if (kind === 'bool') { f.checked = !!data[prop]; return; }
			if (kind === 'occupancyMax') { if (data.occupancy && data.occupancy.maxValue != null) { f.value = data.occupancy.maxValue; } return; }
			if (kind === 'starRating') { if (data.starRating && data.starRating.ratingValue != null) { f.value = data.starRating.ratingValue; } return; }
			if (kind === 'offerPrice') { if (data.offers && data.offers.price != null) { f.value = data.offers.price; } return; }
			if (kind === 'offerCurrency') { if (data.offers && data.offers.priceCurrency) { f.value = data.offers.priceCurrency; } return; }
			if (kind === 'sameAs') { if (Array.isArray(data.sameAs)) { f.value = data.sameAs.join('\n'); } return; }
			if (data[prop] != null) { f.value = data[prop]; }
		});
	}

	populate();
	toggleRows();
	serialize();
	if (typeSel) { typeSel.addEventListener('change', function () { toggleRows(); serialize(); }); }
	fields.forEach(function (f) {
		f.addEventListener('input', serialize);
		f.addEventListener('change', serialize);
	});
	// Base fields, media widget and advanced JSON also refresh the live preview.
	[2, 3, 4, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15].forEach(function (n) {
		var el = document.querySelector('[name="REX_INPUT_VALUE[' + n + ']"]');
		if (el) { el.addEventListener('input', buildPreview); el.addEventListener('change', buildPreview); }
	});
	var previewMedia = document.querySelector('[name="REX_INPUT_MEDIA[1]"]');
	if (previewMedia) { previewMedia.addEventListener('input', buildPreview); previewMedia.addEventListener('change', buildPreview); }
})();
</script>
<script>
(function () {
	var btn = document.getElementById('sd-geocode');
	if (!btn) { return; }
	var msg = document.getElementById('sd-geocode-msg');
	var check = document.getElementById('sd-geocode-check');
	function fieldVal(n) {
		var el = document.querySelector('[name="REX_INPUT_VALUE[' + n + ']"]');
		return el ? el.value.trim() : '';
	}
	btn.addEventListener('click', function () {
		var parts = [fieldVal(9), fieldVal(10), fieldVal(11)].filter(Boolean);
		if (!parts.length) {
			msg.textContent = 'Bitte zuerst die Adresse (Straße, PLZ/Ort, Land) ausfüllen.';
			return;
		}
		msg.textContent = 'Suche Koordinaten …';
		btn.disabled = true;
		fetch('https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=' + encodeURIComponent(parts.join(', ')), { headers: { 'Accept': 'application/json' } })
			.then(function (r) { return r.json(); })
			.then(function (data) {
				btn.disabled = false;
				if (Array.isArray(data) && data.length && data[0].lat && data[0].lon) {
					var lat = data[0].lat;
					var lon = data[0].lon;
					document.getElementById('sd-lat').value = lat;
					document.getElementById('sd-lng').value = lon;
					msg.textContent = 'Koordinaten übernommen: ' + lat + ', ' + lon;
					check.href = 'https://www.openstreetmap.org/?mlat=' + encodeURIComponent(lat) + '&mlon=' + encodeURIComponent(lon) + '#map=17/' + encodeURIComponent(lat) + '/' + encodeURIComponent(lon);
					check.style.display = '';
				} else {
					msg.textContent = 'Keine Koordinaten gefunden. Bitte Adresse prüfen oder manuell eintragen.';
				}
			})
			.catch(function () {
				btn.disabled = false;
				msg.textContent = 'Geocoding fehlgeschlagen (Netzwerk oder Dienst nicht erreichbar).';
			});
	});
})();
</script>
