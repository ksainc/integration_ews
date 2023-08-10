# EWS Connector in Nextcloud

EWS (Exchange Web Services) integration allows you to automatically bidirectionally synchronize your Nextcloud calendars and contacts with an external EWS protocol capable system like Microsoft Exchange, Kerio Connect, etc

![EWS Connector](https://github.com/ksainc/integration_ews/blob/62a4ae5081526009e310ea9e9734809a769697cf/img/EWS%20Notification%20Screen.png?raw=true 'Notifications')

## How to use

Before proceeding further please make sure you read the following sections, Requirements, Installation, Capabilities and Limitations.

After the app is installed and enabled in Nextcloud you should have a new section call "Connected Accounts" under "Personal Settings", if you don't see it refresh the browser window.

![EWS Connector](https://github.com/ksainc/integration_ews/blob/969e0f6a2bfe28e4197c0cc941333a43fce25b5c/img/EWS%20Initial%20Screen.png?raw=true 'Initial Configuration')

On the initial screen you will be asked for the account information (hostname, username, password) for the remote system. After filling in the required information, press the "Connect" button. The app will then attempt an initial connection to the remote system, if successful, it will retrieve a list of all available calendars and contacts folders from the remote system, and display them below the authentication section.

![EWS Connector](https://github.com/ksainc/integration_ews/blob/969e0f6a2bfe28e4197c0cc941333a43fce25b5c/img/EWS%20Connected%20Screen.png?raw=true 'Correlation Configuration')

Once your accounts is connected and the remote calendars and contacts folders are listed similar to the image above. The next step is to create some correlations (associations/relations/links) between the local and remote, calendar or contacts collections by clicking the link icon beside the remote collection name and selecting the local calendar or contacts address book to associate with and click save. PLEASE READ THE WARNING BELOW BEFORE CLICK SAVE

##### WARNING

This is an initial release. Although the app has been thoroughly tested in our environment with live data, we recommend creating a new address book and calendar in both the local and remote systems and linking them together first. Testing the functionality then once satisfied with the results, unlink the test address books/calendars and linking to your live data. To assist in the testing process there are two function buttons "Create Test Data" and "Delete Test Data". The create button will automatically create a new address book and calendar on both systems and populate it with some random contacts and events.

## How it works

The app has two modes of operation, passive and active mode, which are controllable from the Nextcloud administration section "EWS Connector".

##### Passive Mode

In passive mode the synchronization cycle is determined by Nextcloud's internal job scheduling mechanism. That means if the job scheduler is set to run every 5 minutes, the synchronization will be triggered every 5 minutes. The app then checks all configured, calendars and contacts correlations (links/association) for changes and performs a transfer of all compatible data and changes between the local (Nextcloud) and remote (External Server) system.

##### Active Mode

In active mode synchronization between the local (Nextcloud) and remote (External Server) systems is instantaneous. The app listens for events on both the local and remote systems and transfers all compatible data and changes in real time. This mode requires a continuous connection to the remote system and the running of a continuous background process. Please see the requirements section for details.

##### Harmonization

On each synchronization cycle the app checks both the local and remote systems for changes, once a change is detected, the app transfers the changes to the other system. e.g. Items created, updated, deleted in the local system get transferred to the remote and vise versa.

The app keeps harmony of all selected collections (Addressbook/Calendar) and object (Contact/Event) via an internal correlation database. As a secondary, harmonization mechanism, all local and remote objects are also tagged with an UUID (Universal Unique Identifier), this is done to prevent duplicates, and to help in the reconstruction of correlations, in case the remote account gets disconnected. As the local system (Nextcloud) already tags all objects with a UUID, these are then used in the remote system during the creation of new objects. Any objects transfered from the remote system to the local system which are missing a UUID are then updated in the remote system with a UUID after transfer. This fact is mostly only important on initial synchronization as the process of updating all remote objects with a UUID will cause the state of those objects to change, which in turn will cause any other devices or services connected to the remote system to resync those objects. Basically, updating a 1000 (Contacts/Events) on the remote system with a UUID will cause every other device connected to the account to re-download those 1000 (Contacts/Events).

## Requirements

This app has some minimal requirements for passive synchronization and a few more for active synchronization.

##### Basic Requirements

- [Nextcloud](https://nextcloud.com/) instance (≥ 25.0.0)
- PHP  (≥ 7.4.0)
- PHP Curl Package Installed
- PHP Soap Package Installed

##### Active Synchronization Requirements

- PHP CLI Installed
- PHP permission to shell_exec
- PHP access to ps command
- PHP access to kill command

## Installation

It will be available through the Nextcloud app store soon.

But at the moment this app needs to be installed manually by coping all the files to the Nextcloud apps folder (Installation Folder)/apps/integration_ews. Make sure to change the owner of the files to same user as your server.

Once the files are in place, proceed to the apps administration screen and enable it.

## Capabilities and Limitations

The following is a very long list of things that work and limitations.

About 95% of the properties between Nextcloud and EWS are transferable and supported for both Contacts and Calendars, some properties are limited due to the limitations of both systems.

Please note that at the moment the app only supports basic and NTLM authentication. 

### Contacts

The following is a list of contacts properties that translate and are transfered to between both systems.

| Feature | Support Level | Comment |
|---------|---------------|---------|
| Display Name | Supported |  |
| Name - First | Supported |  |
| Name - Last | Supported |  |
| Name - Additional | Supported |  |
| Name - Prefix | Supported |  |
| Name - Suffix | Supported |  |
| Name - Alias | Supported | Nickname |
| Birth Day | Supported |  |
| Gender | Supported |  |
| Photo | Supported |  |
| Occupation - Organization | Supported |  |
| Occupation - Title | Supported |  |
| Occupation - Role | Supported |  |
| Partner | Supported |  |
| Anniversary Day | Supported |  |
| Physical Address(es) | Limited | EWS Limitation<br />1x Work, 1x Home, 1x Other  |
| Email Address(es) | Limited | EWS Limitation  
1x Work, 1x Home, 1x Other  |
| Phone Number(s) | Limited | EWS Limitation  
2x Work, 1x Work Fax, 2x Home, 1x Home Fax, 1x Other, 1x Mobile, 1x Car, 1x Pager |
| Categories | Supported |  |
| Notes | Supported |  |
| Attachment(s) | Not Supported | Will be implemented in future version |
| Instant messaging | Not Supported | Will be implemented in future version |

Any features or properties, not listed in this list are either not supported due to limitations of EWS or not implemented at this time.

###   
Calendars

| Feature | Support Level | Comment |
|---------|---------------|---------|
| Start Time | Supported |  |
| Start Time Zone | Supported |  |
| End Time | Supported |  |
| End Time Zone | Supported |  |
| Label | Supported |  |
| Notes | Supported |  |
| Location | Supported |  |
| Availability | Supported |  |
| Priority | Supported |  |
| Sensitivity | Supported |  |
| Color | Not Supported |  |
| Categories | Supported |  |
| Attachment(s) | Supported |  |
| Attendee(s) - Required | Supported |  |
| Attendee(s) - Optional | Supported |  |
| Attendee(s) - Responses | Supported |  |
| Reminders | Limited | EWS Limitaion<br />Single Reminder Before Event |
| Recurrence - Daily | Supported |  |
| Recurrence  - Weekly | Supported |  |
| Recurrence  - Monthly Absolute | Limited | EWS Limitation<br />Limited to single day of the month |
| Recurrence  - Monthly Relative | Supported |  |
| Recurrence  - Yearly Absolute | Limited | EWS Limitation<br />Limited to single month of the year |
| Recurrence  - Yearly Relative | Limited | EWS Limitation  
Limited to single month of the year |
| Recurrence  - End | Supported |  |

## Future Features
- Add Oauth2 Support
- Add Mail integration