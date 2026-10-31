// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Moves the custom field values printed by an output hook next to an element of the main region.
 *
 * Without JavaScript the values stay where the hook printed them, at the end of the main region.
 *
 * @module     local_fieldssuite/placement
 * @copyright  2026 onwards Kelson da Costa Medeiros
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Moves the values.
 *
 * @param {string} anchor Selector, inside the main region, of the element the values follow; empty or missing
 *     puts them at the top of the main region.
 */
export const init = (anchor) => {
    const main = document.querySelector('#region-main [role="main"]');
    if (!main) {
        return;
    }
    const target = anchor ? main.querySelector(anchor) : null;
    document.querySelectorAll('[data-local-fieldssuite="values"]').forEach((values) => {
        if (target) {
            target.after(values);
        } else {
            main.prepend(values);
        }
    });
};
