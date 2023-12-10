# Changelog

All notable changes to this project will be documented in this file.

## 1.0.15 - 2023-12-09
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