/*
 * Copyright 2016-2018 Martin Baranski, TroubleZone.Net Productions
 *
 * Licensed under the EUPL, Version 1.2 only (the "Licence");
 * You may not use this work except in compliance with the Licence.
 * You may obtain a copy of the Licence at:
 *
 * https://joinup.ec.europa.eu/software/page/eupl
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the Licence is distributed on an "AS IS" basis,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the Licence for the specific language governing permissions and limitations under the Licence.
 *
 * File:    class-editor.js - functions for AYA admin class editor
 * Version: 1.3
 * Date:    2018-01-30
 */

var displayName = 'Klasse';

function getPayload() {
  return {
    ClassID: $('#class-form')[0].dataset.classId,
    Name: $('#class-name').val(),
    PriceLimited: $('#class-price-limited').prop('checked'),
    SortKey: $('#class-sort-key').val()
  };
}
