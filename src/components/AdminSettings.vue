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
		<div class="ews-page-title">
			<EwsIcon :size="32" />
			<h2>{{ t('integration_ews', 'Exchange EWS Connector') }}</h2>
		</div>
		<div class="ews-section-general">
			<div class="description">
				{{ t('integration_ews', 'Select the system settings for Exchange Integration') }}
			</div>
			<div class="parameter">
				<label>
					{{ t('integration_ews', 'Synchronization Mode') }}
				</label>
				<NcSelect v-model="state.harmonization_mode"
					:reduce="item => item.id"
					:options="[{label: 'Passive', id: 'P'}, {label: 'Active', id: 'A'}]" />
			</div>
			<div v-if="state.harmonization_mode === 'A'" class="parameter">
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
			<div v-if="state.harmonization_mode === 'A'" class="parameter">
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
			<div>
				<NcCheckboxRadioSwitch :checked.sync="transportVerification" type="switch">
					{{ t('integration_ews', 'Secure Transport Verification (SSL Certificate Verification). Should always be ON, unless connecting to a Exchange system over an internal LAN.') }}
				</NcCheckboxRadioSwitch>
			</div>
		</div>
		<br>
		<div class="ews-section-ms365">
			<div class="description">
				{{ t('integration_ews', 'Microsoft 365 Authentication Settings') }}
			</div>
			<div class="parameter">
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
					autocapitalize="none"
					:style="{ width: '48ch' }">
			</div>
			<div class="parameter">
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
					autocapitalize="none"
					:style="{ width: '48ch' }">
			</div>
			<div class="parameter">
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
					autocapitalize="none"
					:style="{ width: '48ch' }">
			</div>
		</div>
		<br>
		<div class="ews-section-actions">
			<NcButton @click="onSaveClick()">
				<template #icon>
					<CheckIcon />
				</template>
				{{ t('integration_ews', 'Save') }}
			</NcButton>
		</div>
	</div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'
import { showSuccess, showError } from '@nextcloud/dialogs'

import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import NcSelect from '@nextcloud/vue/dist/Components/NcSelect.js'

import EwsIcon from './icons/EwsIcon.vue'
import CheckIcon from 'vue-material-design-icons/Check.vue'

export default {
	name: 'AdminSettings',

	components: {
		NcButton,
		NcCheckboxRadioSwitch,
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
		transportVerification: {
			get() {
				return (this.state.transport_verification === '1')
			},
			set(value) {
				this.state.transport_verification = (value === true) ? '1' : '0'
			},
		},
	},

	methods: {
		onSaveClick() {
			const req = {
				values: {
					transport_verification: this.state.transport_verification,
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
	.ews-page-title {
		display: flex;
		vertical-align: middle;
	}
	.ews-page-title h2 {
		padding-left: 1%;
	}
	.ews-section-actions {
		display: flex;
		align-items: center;
	}
	.ews-section-general {
		padding-bottom: 1%;
	}
	.ews-section-general .description {
		padding-bottom: 1%;
	}
	.ews-section-general .parameter label {
		display: inline-block;
		width: 32ch;
	}
	.ews-section-ms365 {
		padding-bottom: 1%;
	}
	.ews-section-ms365 .description {
		padding-bottom: 1%;
	}
	.ews-section-ms365 .parameter label {
		display: inline-block;
		width: 24ch;
	}
}
</style>
