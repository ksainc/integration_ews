# Changelog

All notable changes to this project will be documented in this file.

## 1.0.29 - 2024-01-15
### Additions
- Improved transport logging to include errors

## 1.0.28 - 2024-01-14
### Modifications
- Reverted changes from v1.0.27 due to doctraine issue

## 1.0.27 - 2024-01-13
### Modifications
- Changed database remote object state field to blob to fix 65K+ character change key issue

## 1.0.26 - 2024-01-12
### Modifications
- Disabled updated of remote DeletedOccurrences property issue #30

## 1.0.25 - 2024-01-11
### Modifications
- Added extra spacing in transport log
- Fixed event changes retrieval bug, when calendar contains non event items

## 1.0.24 - 2024-01-11
### Additions
- Implemented delete filter for local collections
### Modifications
- Fixed null uuid issue #29
- Refactored Changes Retrieval

## 1.0.23 - 2024-01-10
### Additions
- Added combined short and long uuid validation
- Added remote contact uuid validation
- Added remote task uuid validation
### Modifications
- Fixed variable name and correlation type typo in contacts and tasks service
- Fixed php deprecation warnings
- Fixed php undeclared vaiable warnings
- Fixed remote contact attachment creation issue with Exchange 2007

## 1.0.22 - 2024-01-10
### Additions
- Implemented work around for NC Calendar lack of HTML event descriptions
### Modifications
- Fixed issue with missing alarm values
- Fixed minor issues

## 1.0.21 - 2023-12-22
### Additions
- Added transmission logging for EWS protocol debugging

## 1.0.20 - 2023-12-22
### Modifications
- Improved console connect command and refactored connect process
- Improved console connect command success and failure messages

## 1.0.19 - 2023-12-22
### Additions
- Added error handling for remote public collections, fix for issue #28

## 1.0.18 - 2023-12-21
### Modifications
- Fixed remote delete harmonization issue cause by remote item type check

## 1.0.17 - 2023-12-20
### Modifications
- Imporved User UI error display
- Imporved Admin UI Layout
- Fixed remote UUID update issue
- Fixed short UUID validation issue
- Fixed local attachment creation issue
- Implemented remote duplicate uuid check in hamonization processor
- Implemented remote item type check in hamonization processor

## 1.0.16 - 2023-12-16
### Modifications
- Improved Exchange 2007 Events and Task file attachment support
- Code and Comments clean up in common remote functions
### Deletions
- Removed obsolete code from Contacts, Events and Task hamonization handlers

## 1.0.15 - 2023-12-11
### Additions
- Implemented Support for Windows Active Directory username format
- Implemented mock ews client for offline debugging
### Modifications
- Fixed Basic/NTLM authentication problem when using UTF8 characters Issue #22
- Refactored Contacts, Events and Task Service initialization
- Refactored EWS client
- Improved support for Exchange 2007
- Improved Time Zone matching for EWS Time Zone Description Format
- Improved/Refactored Contact properties conversion (Birth Day, Anniversary Day, Phone Number)
- Improved Event properties conversion (All Day Flag, Reminders)
- Fixed Event recurrence concludes on date inconsistency
### Deletions
- Removed Obsolete Test Code

## 1.0.14 - 2023-11-17
### Modifications
- Fixed Transport Verification Flag Issue #23

## 1.0.13 - 2023-10-16
### Modifications
- Fixed PHP error - Undefined variable

## 1.0.12 - 2023-10-06
### Modifications
- Fixed SOAP error caused by missing service.wsdl and types.xsd definitions

## 1.0.11 - 2023-10-05
### Modifications
- Improved apps availability check to work with apps limited to selected groups. #jkhradil - f29d195
- Fixed UI app name correction. #jkhradil - f29d195

## 1.0.10 - 2023-10-05
### Modifications
- Fixed autodiscovery EXPR definition logic

## 1.0.9 - 2023-10-05
### Additions
- Implemented Support for EXPR protocol definition in auto discovery
### Modifications
- Modified auto discovery logic to include EXPR
- Modified default protocol version to Exchange2007_SP1
- Modified messaging protocol version discovery regex
- Modified user email discovery to use UserSMTPAddress
- Modified connection verification to use "msgfolderroot" instead of "root"
- Updated soap definitiions - Service.wsdl, Messages.xsd, Types.xsd
- Fixed connectAccountAlternate result handler logic in User Configuration Controller

## 1.0.8 - 2023-10-02
### Additions
- Implemented Support for EXHTTP definition in auto discovery
### Modifications
- Improved UI Layout


## 1.0.7 - 2023-09-22
### Modifications
- Minor Code Cleanup
- Improved UI Wording
- Improved On-Premise Connection Logic
- Corrected Lint Errors

## 1.0.6 - 2023-09-21
### Modifications
- Fixed bug with code failure if parameter decryption fails

## 1.0.5 - 2023-09-21
### Modifications
- Added migration script to change remote item id and sync token fileds to unlimited lenght
- Modified mail app provisioning mechanisim. Do not attempt to configure mail app if autodiscovery failed.

## 1.0.3 - 2023-09-21
### Additions
- Added ability to disable SSL verification
- Added request and response, header and body retention and retrival functionality to EWS Client
### Modifications
- Update node and dependencies
- Modified O365 and Alternate client creation meschanis 
- Modified connection settings retieveal and deposit functionallity

## 1.0.1 – 2023-09-01
### Additions
- Added support for Exchange 365 user information
### Modifications
- Improved Oauth Refresh Handling
- Removed Test Buttons

## 1.0.0 – 2023-08-01
### Additions
- First implementation (still experimental)