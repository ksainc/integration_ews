# Exchange EWS Connector (Exchange Web Services) for Nextcloud

EWS (Exchange Web Services) integration allows you to automatically bidirectionally synchronize your Nextcloud calendars and contacts with an external EWS protocol capable system like Microsoft Exchange, Kerio Connect, etc

![EWS Connector](docs/images/EWS%20Notification%20Screen.png 'Notifications')

## How to use

Before proceeding further please make sure you read the following sections, Requirements, Installation, Capabilities and Limitations.

After the app is installed and enabled in Nextcloud you should have a new section called "Connected Accounts" under "Personal Settings", if you don't see it refresh the browser window.

![EWS Connector](docs/images/EWS%20Initial%20Screen.png 'Initial Configuration')

### On-premises / Self-Hosted Exchange
On the initial screen you will be asked for the account information hostname (formats IP/domain), username (formats "user@domain.local" or "domain\username"), password for the remote system. After filling in the required information, press the "Connect" button. The app will then attempt an initial connection to the remote system, if successful, it will retrieve a list of all available contacts, calendar and task folders from the remote system, and display them below the authentication section.

### Exchange 365
To connect to a Office 365 Account the system administrator for your organization needs to pre-setup the appropriate settings in both the Office 365 control panel and Nextcloud. Please see [Microsoft Exchange 365 Configuration](docs/o365.md)

After the above inital configuration has been completed. On the initial configuration screen change the provider to "Microsoft Exchange 365". Then press the connect button, a popup will appear asking for your account information, upon successful authentication the app will then attempt an initial connection to the remote system, if successful, it will retrieve a list of all available contacts, calendar and task folders from the remote system, and display them below the authentication section.

![EWS Connector](docs/images/EWS%20Connected%20Screen.png 'Correlation Configuration')

Once your accounts is connected and the remote calendars and contacts folders are listed, similar to the image above. The next step is to create some correlations (associations/relations/links) between the local and remote, contacts, calendar or task collections by clicking the link icon beside the remote collection name and selecting the local addressbook, calendar or tasks list to associate with and click save.

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

Please read the requirements section and make sure the PHP requirements are met.

Then search for "Exchange EWS Connector" in your apps manager, click download then enable.

Configure the application in the personal settings section.

## Capabilities and Limitations

The following is a very long list of things that work and limitations.

- Supported On-Premise Exchange Systems: Echange 2007-2022, GFI Kerio Connect, SmarterMail, Etc
- Supported Off-Premise Exchange Systems: Microsoft Exchage 365, OVH Cloud, Etc
- Supported Authentications: Basic, NTLM, OAuth2
- Passive (scheduled) and Active (live) Syncronization
- Account Auto Discovery
- Personal and Public Calendar Syncronization
- Personal and Public Contacts Syncronization
- Personal and Public Tasks Syncronization
- Mail App Integration

About 95% of the properties between Nextcloud and EWS are transferable and supported for both Contacts, Calendars and Tasks, some properties are limited due to the limitations of both systems.

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
| Reminders | Limited | EWS Limitation<br />Relative And Absolute with negative value only. Absolute is converted to Relative. |

## Future Features
- Add Notes Syncronization Support