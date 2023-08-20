<!--
*
* @copyright Copyright (c) 2023 Sebastian Krupinski <krupinski01@gmail.com>
*
* @author Sebastian Krupinski <krupinski01@gmail.com>
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
-->

<template>
	<div id="ews_settings" class="section">
		<div class="ews-section-heading">
			<EwsIcon :size="32" /><h2> {{ t('integration_ews', 'EWS Connector') }}</h2>
		</div>
		<p class="settings-hint">
			{{ t('integration_ews', 'Select the system settings for Exchange Integration') }}
		</p>
		<div class="fields">
			<div>
				<div class="line">
					<label>
						{{ t('integration_ews', 'Synchronization Mode') }}
					</label>
					<NcSelect v-model="state.harmonization_mode"
						:reduce="item => item.id"
						:options="[{label: 'Passive', id: 'P'}, {label: 'Active', id: 'A'}]" />
				</div>
				<div v-if="state.harmonization_mode === 'A'" class="line">
					<label>
						{{ t('integration_ews', 'Synchronization Thread Duration') }}
					</label>
					<input id="ews-thread-duration"
						v-model="state.harmonization_thread_duration"
						type="text"
						:autocomplete="'off'"
						:autocorrect="'off'"
						:autocapitalize="'none'">
					<label>
						{{ t('integration_ews', 'Seconds') }}
					</label>
				</div>
				<div v-if="state.harmonization_mode === 'A'" class="line">
					<label>
						{{ t('integration_ews', 'Synchronization Thread Pause') }}
					</label>
					<input id="ews-thread-pause"
						v-model="state.harmonization_thread_pause"
						type="text"
						:autocomplete="off"
						:autocorrect="off"
						:autocapitalize="none">
					<label>
						{{ t('integration_ews', 'Seconds') }}
					</label>
				</div>
			</div>
			<br>
			<div>
				<p class="settings-hint">
					{{ t('integration_ews', 'Microsoft 365 Authentication Settings') }}
				</p>
				<div class="line">
					<label for="ews-microsoft-tenant-id">
						<EwsIcon />
						{{ t('integration_ews', 'Tenant ID') }}
					</label>
					<input id="ews-microsoft-tenant-id"
						v-model="state.ms365_tenant_id"
						type="text"
						:placeholder="t('integration_ews', '')"
						autocomplete="off"
						autocorrect="off"
						autocapitalize="none">
				</div>
				<div class="line">
					<label for="ews-microsoft-application-id">
						<EwsIcon />
						{{ t('integration_ews', 'Application ID') }}
					</label>
					<input id="ews-microsoft-application-id"
						v-model="state.ms365_application_id"
						type="text"
						:placeholder="t('integration_ews', '')"
						autocomplete="off"
						autocorrect="off"
						autocapitalize="none">
				</div>
				<div class="line">
					<label for="ews-microsoft-application-secret">
						<EwsIcon />
						{{ t('integration_ews', 'Application Secret') }}
					</label>
					<input id="ews-microsoft-application-secret"
						v-model="state.ms365_application_secret"
						type="password"
						:placeholder="t('integration_ews', '')"
						autocomplete="off"
						autocorrect="off"
						autocapitalize="none">
				</div>
			</div>
			<br>
			<div class="ews-actions">
				<NcButton @click="onSaveClick()">
					<template #icon>
						<CheckIcon />
					</template>
					{{ t('integration_ews', 'Save') }}
				</NcButton>
			</div>
		</div>
	</div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'
import { showSuccess, showError } from '@nextcloud/dialogs'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcSelect from '@nextcloud/vue/dist/Components/NcSelect.js'

import EwsIcon from './icons/EwsIcon.vue'
import CheckIcon from 'vue-material-design-icons/Check.vue'

export default {
	name: 'AdminSettings',

	components: {
		NcButton,
		NcSelect,
		EwsIcon,
		CheckIcon,
	},

	props: [],

	data() {
		return {
			readonly: true,
			state: loadState('integration_ews', 'admin-configuration'),
		}
	},

	computed: {
	},

	methods: {
		onSaveClick() {
			const req = {
				values: {
					harmonization_mode: this.state.harmonization_mode,
					harmonization_thread_duration: this.state.harmonization_thread_duration,
					harmonization_thread_pause: this.state.harmonization_thread_pause,
					ms365_tenant_id: this.state.ms365_tenant_id,
					ms365_application_id: this.state.ms365_application_id,
					ms365_application_secret: this.state.ms365_application_secret,
				},
			}
			const url = generateUrl('/apps/integration_ews/admin-configuration')
			axios.put(url, req)
				.then((response) => {
					showSuccess(t('integration_ews', 'EWS admin configuration saved'))
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to save EWS admin configuration')
						+ ': ' + error.response.request.responseText
					)
				})
				.then(() => {
				})
		},
	},
}
</script>

<style scoped lang="scss">
#ews_settings {
	.ews-section-heading {
		display:inline-block;
		vertical-align:middle;
	}

	.ews-connected {
		display: flex;
		align-items: center;

		label {
			padding-left: 1em;
			padding-right: 1em;
		}
	}

	.ews-collectionlist-item {
		display: flex;
		align-items: center;

		label {
			padding-left: 1em;
			padding-right: 1em;
		}
	}

	.ews-actions {
		display: flex;
		align-items: center;
	}

	.external-label {
		display: flex;
		//width: 100%;
		margin-top: 1rem;
	}

	.external-label label {
		padding-top: 7px;
		padding-right: 14px;
		white-space: nowrap;
	}
}
</style>
