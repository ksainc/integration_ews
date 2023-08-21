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
		<div class="ews-content">
			<h3>{{ t('integration_ews', 'Authentication') }}</h3>
			<div v-if="state.account_connected != 1">
				<div>
					<label>
						{{ t('integration_ews', 'Provider ') }}
					</label>
					<NcSelect v-model="state.account_provider"
						:reduce="item => item.id"
						:options="[{label: 'On-Premises / Alternate', id: 'A'}, {label: 'Microsoft Exchange 365 Online', id: 'MS365'}]" />
				</div>
				<br>
				<div v-if="state.account_provider == 'MS365'">
					<div class="fields">
						<div v-if="state.system_ms365_authrization_uri === ''">
							{{ t('integration_ews', 'No Microsoft Exchange 365 configuration missing. Ask your Nextcloud administrator to configure Microsoft Exchange 365 connected accounts admin section.') }}
						</div>
						<div v-else class="ews-connect-ms365">
							<label>
								{{ t('integration_ews', 'Press connect and enter your account information') }}
							</label>
							<NcButton @click="onConnectMS365Click">
								<template #icon>
									<CheckIcon />
								</template>
								{{ t('integration_ews', 'Connect') }}
							</NcButton>
						</div>
					</div>
				</div>
				<div v-else>
					<div class="settings-hint">
						{{ t('integration_ews', 'Enter your Exchange Server and account information then press connect.') }}
					</div>
					<div class="fields">
						<div class="line">
							<label for="ews-account-id">
								<EwsIcon />
								{{ t('integration_ews', 'Account ID') }}
							</label>
							<input id="ews-account-id"
								v-model="state.account_id"
								type="text"
								:placeholder="t('integration_ews', 'Authentication ID for your EWS Account')"
								autocomplete="off"
								autocorrect="off"
								autocapitalize="none">
						</div>
						<div class="line">
							<label for="ews-account-secret">
								<EwsIcon />
								{{ t('integration_ews', 'Account Secret') }}
							</label>
							<input id="ews-account-secret"
								v-model="state.account_secret"
								type="password"
								:placeholder="t('integration_ews', 'Authentication secret for your EWS Account')"
								autocomplete="off"
								autocorrect="off"
								autocapitalize="none">
						</div>
						<div v-if="configureManually" class="line">
							<label for="ews-server">
								<EwsIcon />
								{{ t('integration_ews', 'Account Server') }}
							</label>
							<input id="ews-server"
								v-model="state.account_server"
								type="text"
								:placeholder="t('integration_ews', 'Account Server Address')"
								autocomplete="off"
								autocorrect="off"
								autocapitalize="none">
						</div>
						<div>
							<NcCheckboxRadioSwitch :checked.sync="configureManually" type="switch">
								{{ t('integration_ews', 'Configure server manually') }}
							</NcCheckboxRadioSwitch>
						</div>
						<div>
							<NcCheckboxRadioSwitch :checked.sync="configureMail" type="switch">
								{{ t('integration_ews', 'Configure mail app on successful connection') }}
							</NcCheckboxRadioSwitch>
						</div>
						<div class="line">
							<label class="ews-connect">
								&nbsp;
							</label>
							<NcButton @click="onConnectAlternateClick">
								<template #icon>
									<CheckIcon />
								</template>
								{{ t('integration_ews', 'Connect') }}
							</NcButton>
						</div>
					</div>
				</div>
			</div>
			<div v-else>
				<div class="ews-connected">
					<EwsIcon />
					<label>
						{{ t('integration_ews', 'Connected as {0} to {1}', {0:state.account_id, 1:state.account_server}) }}
					</label>
					<NcButton @click="onDisconnectClick">
						<template #icon>
							<CloseIcon />
						</template>
						{{ t('integration_ews', 'Disconnect') }}
					</NcButton>
				</div>
				<div>
					{{ t('integration_ews', 'Synchronization was last started on ') }} {{ formatDate(state.account_harmonization_start) }}
					{{ t('integration_ews', 'and finished on ') }} {{ formatDate(state.account_harmonization_end) }}
				</div>
				<br>
				<div class="ews-correlations-contacts">
					<h3>{{ t('integration_ews', 'Contacts') }}</h3>
					<div class="settings-hint">
						{{ t('integration_ews', 'Select the remote contacts folder(s) you wish to synchronize by pressing the link button next to the contact folder name and selecting the local contacts address book to synchronize to.') }}
					</div>
					<div v-if="state.system_contacts == 1">
						<ul v-if="availableRemoteContactCollections.length > 0">
							<li v-for="ritem in availableRemoteContactCollections" :key="ritem.id" class="ews-collectionlist-item">
								<ContactIcon />
								<label>
									{{ ritem.name }} ({{ ritem.count }} Contacts)
								</label>
								<NcActions>
									<template #icon>
										<LinkIcon />
									</template>
									<NcActionButton @click="clearContactCorrelation(ritem.id)">
										<template #icon>
											<CloseIcon />
										</template>
										Clear
									</NcActionButton>
									<NcActionRadio v-for="litem in availableLocalContactCollections"
										:key="litem.id"
										:disabled="establishedContactCorrelationDisable(ritem.id, litem.id)"
										:checked="establishedContactCorrelationSelect(ritem.id, litem.id)"
										@change="changeContactCorrelation(ritem.id, litem.id)">
										{{ litem.name }}
									</NcActionRadio>
								</NcActions>
							</li>
						</ul>
						<div v-else-if="availableRemoteContactCollections.length == 0">
							{{ t('integration_ews', 'No contacts collections where found in the connected account.') }}
						</div>
						<div v-else>
							{{ t('integration_ews', 'Loading contacts collections from the connected account.') }}
						</div>
						<br>
						<div>
							<label>
								{{ t('integration_ews', 'Synchronize ') }}
							</label>
							<NcSelect v-model="state.contacts_harmonize"
								:reduce="item => item.id"
								:options="[{label: 'Never', id: '-1'}, {label: 'Manually', id: '0'}, {label: 'Automatically', id: '5'}]" />
							<label>
								{{ t('integration_ews', 'and if there is a conflict') }}
							</label>
							<NcSelect v-model="state.contacts_prevalence"
								:reduce="item => item.id"
								:options="[{label: 'Remote', id: 'R'}, {label: 'Local', id: 'L'}, {label: 'Chronology', id: 'C'}]" />
							<label>
								{{ t('integration_ews', 'prevails') }}
							</label>
						</div>
						<br>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these local actions to the Remote system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_local" value="c" name="contacts_actions_local">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_local" value="u" name="contacts_actions_local">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_local" value="d" name="contacts_actions_local">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these remote actions to the local system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_remote" value="c" name="contacts_actions_remote">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_remote" value="u" name="contacts_actions_remote">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.contacts_actions_remote" value="d" name="contacts_actions_remote">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
					</div>
					<div v-else>
						{{ t('integration_ews', 'The contacts app is either disabled or not installed. Please contact your administrator to install or enable the app.') }}
					</div>
					<br>
				</div>
				<div class="ews-correlations-events">
					<h3>{{ t('integration_ews', 'Calendars') }}</h3>
					<div class="settings-hint">
						{{ t('integration_ews', 'Select the remote calendar(s) you wish to synchronize by pressing the link button next to the calendars name and selecting the local calendar to synchronize to.') }}
					</div>
					<div v-if="state.system_events == 1">
						<ul v-if="availableRemoteEventCollections.length > 0">
							<li v-for="ritem in availableRemoteEventCollections" :key="ritem.id" class="ews-collectionlist-item">
								<CalendarIcon />
								<label>
									{{ ritem.name }} ({{ ritem.count }} Events)
								</label>
								<NcActions>
									<template #icon>
										<LinkIcon />
									</template>
									<NcActionButton @click="clearEventCorrelation(ritem.id)">
										<template #icon>
											<CloseIcon />
										</template>
										Clear
									</NcActionButton>
									<NcActionRadio v-for="litem in availableLocalEventCollections"
										:key="litem.id"
										:disabled="establishedEventCorrelationDisable(ritem.id, litem.id)"
										:checked="establishedEventCorrelationSelect(ritem.id, litem.id)"
										@change="changeEventCorrelation(ritem.id, litem.id)">
										{{ litem.name }}
									</NcActionRadio>
								</NcActions>
							</li>
						</ul>
						<div v-else-if="availableRemoteEventCollections.length == 0">
							{{ t('integration_ews', 'No events collections where found in the connected account.') }}
						</div>
						<div v-else>
							{{ t('integration_ews', 'Loading events collections from the connected account.') }}
						</div>
						<br>
						<div>
							<label>
								{{ t('integration_ews', 'Synchronize ') }}
							</label>
							<NcSelect v-model="state.events_harmonize"
								:reduce="item => item.id"
								:options="[{label: 'Never', id: '-1'}, {label: 'Manually', id: '0'}, {label: 'Automatically', id: '5'}]" />
							<label>
								{{ t('integration_ews', 'and if there is a conflict') }}
							</label>
							<NcSelect v-model="state.events_prevalence"
								:reduce="item => item.id"
								:options="[{label: 'Remote', id: 'R'}, {label: 'Local', id: 'L'}, {label: 'Chronology', id: 'C'}]" />
							<label>
								{{ t('integration_ews', 'prevails') }}
							</label>
						</div>
						<br>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these local actions to the Remote system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_local" value="c" name="events_actions_local">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_local" value="u" name="events_actions_local">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_local" value="d" name="events_actions_local">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these remote actions to the local system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_remote" value="c" name="events_actions_remote">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_remote" value="u" name="events_actions_remote">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.events_actions_remote" value="d" name="events_actions_remote">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
					</div>
					<div v-else>
						{{ t('integration_ews', 'The contacts app is either disabled or not installed. Please contact your administrator to install or enable the app.') }}
					</div>
					<br>
				</div>
				<div class="ews-correlations-tasks">
					<h3>{{ t('integration_ews', 'Tasks') }}</h3>
					<div class="settings-hint">
						{{ t('integration_ews', 'Select the remote Task(s) folder you wish to synchronize by pressing the link button next to the folder name and selecting the local calendar to synchronize to.') }}
					</div>
					<div v-if="state.system_tasks == 1">
						<ul v-if="availableRemoteTaskCollections.length > 0">
							<li v-for="ritem in availableRemoteTaskCollections" :key="ritem.id" class="ews-collectionlist-item">
								<CalendarIcon />
								<label>
									{{ ritem.name }} ({{ ritem.count }} Tasks)
								</label>
								<NcActions>
									<template #icon>
										<LinkIcon />
									</template>
									<NcActionButton @click="clearTaskCorrelation(ritem.id)">
										<template #icon>
											<CloseIcon />
										</template>
										Clear
									</NcActionButton>
									<NcActionRadio v-for="litem in availableLocalTaskCollections"
										:key="litem.id"
										:disabled="establishedTaskCorrelationDisable(ritem.id, litem.id)"
										:checked="establishedTaskCorrelationSelect(ritem.id, litem.id)"
										@change="changeTaskCorrelation(ritem.id, litem.id)">
										{{ litem.name }}
									</NcActionRadio>
								</NcActions>
							</li>
						</ul>
						<div v-else-if="availableRemoteTaskCollections.length == 0">
							{{ t('integration_ews', 'No tasks collections where found in the connected account.') }}
						</div>
						<div v-else>
							{{ t('integration_ews', 'Loading tasks collections from the connected account.') }}
						</div>
						<br>
						<div>
							<label>
								{{ t('integration_ews', 'Synchronize ') }}
							</label>
							<NcSelect v-model="state.tasks_harmonize"
								:reduce="item => item.id"
								:options="[{label: 'Never', id: '-1'}, {label: 'Manually', id: '0'}, {label: 'Automatically', id: '5'}]" />
							<label>
								{{ t('integration_ews', 'and if there is a conflict') }}
							</label>
							<NcSelect v-model="state.tasks_prevalence"
								:reduce="item => item.id"
								:options="[{label: 'Remote', id: 'R'}, {label: 'Local', id: 'L'}, {label: 'Chronology', id: 'C'}]" />
							<label>
								{{ t('integration_ews', 'prevails') }}
							</label>
						</div>
						<br>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these local actions to the Remote system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_local" value="c" name="tasks_actions_local">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_local" value="u" name="tasks_actions_local">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_local" value="d" name="tasks_actions_local">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
						<div v-if="false" style="display: flex">
							<label>
								{{ t('integration_ews', 'Syncronized these remote actions to the local system') }}
							</label>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_remote" value="c" name="tasks_actions_remote">
								Create
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_remote" value="u" name="tasks_actions_remote">
								Update
							</NcCheckboxRadioSwitch>
							<NcCheckboxRadioSwitch :checked.sync="state.tasks_actions_remote" value="d" name="tasks_actions_remote">
								Delete
							</NcCheckboxRadioSwitch>
						</div>
					</div>
					<div v-else>
						{{ t('integration_ews', 'The contacts app is either disabled or not installed. Please contact your administrator to install or enable the app.') }}
					</div>
					<br>
				</div>
				<div class="ews-actions">
					<NcButton @click="onSaveClick()">
						<template #icon>
							<CheckIcon />
						</template>
						{{ t('integration_ews', 'Save') }}
					</NcButton>
					<NcButton @click="onHarmonizeClick()">
						<template #icon>
							<LinkIcon />
						</template>
						{{ t('integration_ews', 'Sync') }}
					</NcButton>
					<NcButton @click="onTestClick('C')">
						<template #icon>
							<LinkIcon />
						</template>
						{{ t('integration_ews', 'Create Test Data') }}
					</NcButton>
					<NcButton @click="onTestClick('D')">
						<template #icon>
							<LinkIcon />
						</template>
						{{ t('integration_ews', 'Delete Test Data') }}
					</NcButton>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import axios from '@nextcloud/axios'
import { generateUrl } from '@nextcloud/router'
import { loadState } from '@nextcloud/initial-state'
import { showSuccess, showError } from '@nextcloud/dialogs'

import NcActions from '@nextcloud/vue/dist/Components/NcActions.js'
import NcActionButton from '@nextcloud/vue/dist/Components/NcActionButton.js'
import NcActionRadio from '@nextcloud/vue/dist/Components/NcActionRadio.js'
import NcButton from '@nextcloud/vue/dist/Components/NcButton.js'
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import NcSelect from '@nextcloud/vue/dist/Components/NcSelect.js'

import EwsIcon from './icons/EwsIcon.vue'
import CheckIcon from 'vue-material-design-icons/Check.vue'
import CloseIcon from 'vue-material-design-icons/Close.vue'
import CalendarIcon from 'vue-material-design-icons/Calendar.vue'
import ContactIcon from 'vue-material-design-icons/ContactsOutline.vue'
import LinkIcon from 'vue-material-design-icons/Link.vue'

export default {
	name: 'UserSettings',

	components: {
		NcActions,
		NcActionButton,
		NcActionRadio,
		NcButton,
		NcCheckboxRadioSwitch,
		NcSelect,
		EwsIcon,
		CheckIcon,
		CloseIcon,
		CalendarIcon,
		ContactIcon,
		LinkIcon,
	},

	props: [],

	data() {
		return {
			readonly: true,
			state: loadState('integration_ews', 'user-configuration'),
			// contacts
			availableRemoteContactCollections: [],
			availableLocalContactCollections: [],
			establishedContactCorrelations: [],
			// calendars
			availableRemoteEventCollections: [],
			availableLocalEventCollections: [],
			establishedEventCorrelations: [],
			// tasks
			availableRemoteTaskCollections: [],
			availableLocalTaskCollections: [],
			establishedTaskCorrelations: [],

			configureManually: false,
			configureMail: false,
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
		this.loadData()
	},

	methods: {
		test() {
			showSuccess()
		},
		loadData() {
			// get collections list if we are connected
			if (this.state.account_connected === '1') {
				this.fetchCorrelations()
				this.fetchLocalCollections()
				this.fetchRemoteCollections()
			}
		},
		onConnectAlternateClick() {
			const uri = generateUrl('/apps/integration_ews/connect-alternate')
			const data = {
				params: {
					account_id: this.state.account_id,
					account_secret: this.state.account_secret,
					account_server: this.state.account_server,
					flag: this.configureMail,
				},
			}
			axios.get(uri, data)
				.then((response) => {
					if (response.data === 'success') {
						showSuccess(('Successfully connected to EWS account'))
						this.state.account_connected = 1
						this.fetchPreferences()
						this.loadData()
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to authenticate with EWS server')
						+ ': ' + error.response?.request?.responseText
					)
				})
		},
		onConnectMS365Click() {
			const ssoWindow = window.open(
				this.state.system_ms365_authrization_uri,
				t('integration_onedrive', 'Sign in Nextcloud EWS Connector'),
				' width=600, height=700'
			)
			ssoWindow.focus()
			window.addEventListener('message', (event) => {
				console.debug('Child window message received', event)
				this.state.account_connected = 1
				this.fetchPreferences()
				this.loadData()
			})
		},
		onDisconnectClick() {
			const uri = generateUrl('/apps/integration_ews/disconnect')
			axios.get(uri)
				.then((response) => {
					showSuccess(('Successfully disconnected from EWS account'))
					// state
					this.fetchPreferences()
					// contacts
					this.availableRemoteContactCollections = []
					this.availableLocalContactCollections = []
					this.establishedContactCorrelations = []
					// events
					this.availableRemoteEventCollections = []
					this.availableLocalEventCollections = []
					this.establishedEventCorrelations = []
					// tasks
					this.availableRemoteTaskCollections = []
					this.availableLocalTaskCollections = []
					this.establishedTaskCorrelations = []
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to disconnect from EWS account')
						+ ': ' + error.response?.request?.responseText
					)
				})
				.then(() => {
				})
		},
		onSaveClick() {
			this.depositPreferences({
				contacts_prevalence: this.state.contacts_prevalence,
				contacts_harmonize: this.state.contacts_harmonize,
				contacts_actions_local: this.state.contacts_actions_local,
				contacts_actions_remote: this.state.contacts_actions_remote,
				events_prevalence: this.state.events_prevalence,
				events_harmonize: this.state.events_harmonize,
				events_actions_local: this.state.events_actions_local,
				events_actions_remote: this.state.events_actions_remote,
				tasks_prevalence: this.state.tasks_prevalence,
				tasks_harmonize: this.state.tasks_harmonize,
				tasks_actions_local: this.state.tasks_actions_local,
				tasks_actions_remote: this.state.tasks_actions_remote,
			})
			this.depositCorrelations()
		},
		onHarmonizeClick() {
			const uri = generateUrl('/apps/integration_ews/harmonize')
			axios.get(uri)
				.then((response) => {
					showSuccess('Synchronization Successful')
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Synchronization Failed')
						+ ': ' + error.response?.request?.responseText
					)
				})
		},
		onTestClick(action) {
			const uri = generateUrl('/apps/integration_ews/test')
			const data = {
				params: {
					action,
				},
			}
			axios.get(uri, data)
				.then((response) => {
					showSuccess('Test Successful')
					this.loadData()
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Test Failed')
						+ ': ' + error.response?.request?.responseText
					)
				})
		},
		fetchRemoteCollections() {
			const uri = generateUrl('/apps/integration_ews/fetch-remote-collections')
			axios.get(uri)
				.then((response) => {
					if (response.data.ContactCollections) {
						this.availableRemoteContactCollections = response.data.ContactCollections
						showSuccess(('Found ' + this.availableRemoteContactCollections.length + ' Remote Contacts Collections'))
					}
					if (response.data.EventCollections) {
						this.availableRemoteEventCollections = response.data.EventCollections
						showSuccess(('Found ' + this.availableRemoteEventCollections.length + ' Remote Events Collections'))
					}
					if (response.data.TaskCollections) {
						this.availableRemoteTaskCollections = response.data.TaskCollections
						showSuccess(('Found ' + this.availableRemoteTaskCollections.length + ' Remote Tasks Collections'))
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to load remote collections list')
						+ ': ' + error.response?.request?.responseText
					)
				})
				.then(() => {
				})
		},
		fetchLocalCollections() {
			const uri = generateUrl('/apps/integration_ews/fetch-local-collections')
			axios.get(uri)
				.then((response) => {
					if (response.data.ContactCollections) {
						this.availableLocalContactCollections = response.data.ContactCollections
						showSuccess(('Found ' + this.availableLocalContactCollections.length + ' Local Contacts Collections'))
					}
					if (response.data.EventCollections) {
						this.availableLocalEventCollections = response.data.EventCollections
						showSuccess(('Found ' + this.availableLocalEventCollections.length + ' Local Events Collections'))
					}
					if (response.data.TaskCollections) {
						this.availableLocalTaskCollections = response.data.TaskCollections
						showSuccess(('Found ' + this.availableLocalTaskCollections.length + ' Local Tasks Collections'))
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to load local collections list')
						+ ': ' + error.response?.request?.responseText
					)
				})
				.then(() => {
				})
		},
		fetchCorrelations() {
			const uri = generateUrl('/apps/integration_ews/fetch-correlations')
			axios.get(uri)
				.then((response) => {
					if (response.data.ContactCorrelations) {
						this.establishedContactCorrelations = response.data.ContactCorrelations
						showSuccess(('Found ' + this.establishedContactCorrelations.length + ' Contact Collection Correlations'))
					}
					if (response.data.EventCorrelations) {
						this.establishedEventCorrelations = response.data.EventCorrelations
						showSuccess(('Found ' + this.establishedEventCorrelations.length + ' Event Collection Correlations'))
					}
					if (response.data.TaskCorrelations) {
						this.establishedTaskCorrelations = response.data.TaskCorrelations
						showSuccess(('Found ' + this.establishedTaskCorrelations.length + ' Task Collection Correlations'))
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to load collection correlations list')
						+ ': ' + error.response?.request?.responseText
					)
				})
				.then(() => {
				})
		},
		depositCorrelations() {
			const uri = generateUrl('/apps/integration_ews/deposit-correlations')
			const data = {
				ContactCorrelations: this.establishedContactCorrelations,
				EventCorrelations: this.establishedEventCorrelations,
				TaskCorrelations: this.establishedTaskCorrelations,
			}
			axios.put(uri, data)
				.then((response) => {
					showSuccess('Saved correlations')
					if (response.data.ContactCorrelations) {
						this.establishedContactCorrelations = response.data.ContactCorrelations
						showSuccess('Found ' + this.establishedContactCorrelations.length + ' Contact Collection Correlations')
					}
					if (response.data.EventCorrelations) {
						this.establishedEventCorrelations = response.data.EventCorrelations
						showSuccess('Found ' + this.establishedEventCorrelations.length + ' Event Collection Correlations')
					}
					if (response.data.TaskCorrelations) {
						this.establishedTaskCorrelations = response.data.TaskCorrelations
						showSuccess('Found ' + this.establishedTaskCorrelations.length + ' Task Collection Correlations')
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to save correlations') + ': ' + error.response?.request?.responseText
					)
				})
				.then(() => {
				})

		},
		fetchPreferences() {
			const uri = generateUrl('/apps/integration_ews/fetch-preferences')
			axios.get(uri)
				.then((response) => {
					if (response.data) {
						this.state = response.data
					}
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to retrieve preferences')
						+ ': ' + error.response.request.responseText
					)
				})
				.then(() => {
				})
		},
		depositPreferences(values) {
			const data = {
				values,
			}
			const uri = generateUrl('/apps/integration_ews/deposit-preferences')
			axios.put(uri, data)
				.then((response) => {
					showSuccess(t('integration_ews', 'Saved preferences'))
				})
				.catch((error) => {
					showError(
						t('integration_ews', 'Failed to save preferences')
						+ ': ' + error.response.request.responseText
					)
				})
				.then(() => {
				})
		},
		changeContactCorrelation(roid, loid) {
			const cid = this.establishedContactCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid === -1) {
				this.establishedContactCorrelations.push({ id: null, roid, loid, type: 'CC', action: 'C' })
			} else {
				this.establishedContactCorrelations[cid].loid = loid
				this.establishedContactCorrelations[cid].action = 'U'
			}
		},
		changeEventCorrelation(roid, loid) {
			const cid = this.establishedEventCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid === -1) {
				this.establishedEventCorrelations.push({ id: null, roid, loid, type: 'EC', action: 'C' })
			} else {
				this.establishedEventCorrelations[cid].loid = loid
				this.establishedEventCorrelations[cid].action = 'U'
			}
		},
		changeTaskCorrelation(roid, loid) {
			const cid = this.establishedTaskCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid === -1) {
				this.establishedTaskCorrelations.push({ id: null, roid, loid, type: 'TC', action: 'C' })
			} else {
				this.establishedTaskCorrelations[cid].loid = loid
				this.establishedTaskCorrelations[cid].action = 'U'
			}
		},
		clearContactCorrelation(roid) {
			const cid = this.establishedContactCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid > -1) {
				this.establishedContactCorrelations[cid].roid = null
				this.establishedContactCorrelations[cid].loid = null
				this.establishedContactCorrelations[cid].action = 'D'
				// this.establishedContactCorrelations.splice(cid, 1)
			}
		},
		clearEventCorrelation(roid) {
			const cid = this.establishedEventCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid > -1) {
				this.establishedEventCorrelations[cid].roid = null
				this.establishedEventCorrelations[cid].loid = null
				this.establishedEventCorrelations[cid].action = 'D'
				// this.establishedEventCorrelations.splice(cid, 1)
			}
		},
		clearTaskCorrelation(roid) {
			const cid = this.establishedTaskCorrelations.findIndex(i => String(i.roid) === String(roid))

			if (cid > -1) {
				this.establishedTaskCorrelations[cid].roid = null
				this.establishedTaskCorrelations[cid].loid = null
				this.establishedTaskCorrelations[cid].action = 'D'
				// this.establishedEventCorrelations.splice(cid, 1)
			}
		},
		establishedContactCorrelationDisable(roid, loid) {
			const citem = this.establishedContactCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid !== roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		establishedContactCorrelationSelect(roid, loid) {
			const citem = this.establishedContactCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid === roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		establishedEventCorrelationDisable(roid, loid) {
			const citem = this.establishedEventCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid !== roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		establishedEventCorrelationSelect(roid, loid) {
			const citem = this.establishedEventCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid === roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		establishedTaskCorrelationDisable(roid, loid) {
			const citem = this.establishedTaskCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid !== roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		establishedTaskCorrelationSelect(roid, loid) {
			const citem = this.establishedTaskCorrelations.find(i => String(i.loid) === String(loid))

			// console.log('ECC Item - LID: ' + this.establishedContactCorrelations[0].loid + ' RID: ' + this.establishedContactCorrelations[0].roid)
			// console.log('R Item ID ' + roid)
			// console.log('L Item ID ' + loid)

			if (typeof citem !== 'undefined') {
				if (citem.roid === roid) {
					// console.log('Logic True - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return true
				} else {
					// console.log('Logic False - C Item RID: ' + citem.roid + ' R Item ID: ' + roid)
					return false
				}
			} else {
				// console.log('Logic undefined')
				return false
			}
		},
		formatDate(dt) {
			if (dt) {
				return (new Date(dt * 1000)).toLocaleString()
			} else {
				return 'never'
			}
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
