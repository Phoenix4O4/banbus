# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project loosely adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## Upcoming

- Check out the [todo list](https://hackmd.io/Ojsm71RhT2-r5dzf-a16cw).
---

## [0.14.0] - 2022-01-26

### Changed
- Forum authentication now uses TGStation's OAuth provider instead of the OAuth-like system that was in use.

---

## [0.13.3] - 2021-09-28

### Added
- Support for `Interaction` admin helps (See [tgstation/62994](https://github.com/tgstation/tgstation/pull/62994))
- Option to pass a config to HTMLPurifier

---

## [0.13.2] - 2021-09-28

### Added
- A search bar on the rounds page will let you quickly jump to whatever round ID you input

---

## [0.13.1] - 2021-09-28

### Added
- Admins can now view all books (including deleted ones) by any ckey

### Fixed
- Library search was not actually configured
- An error that was always being triggered when a book was reported or deleted

---

## [0.13.1] - 2021-09-28

### Added
- Library search feature

---

## [0.13.0] - 2021-09-28

### Added
- Support for authenticating with Statbus using Discord! The future is NOW!

---

## [0.12.0] - 2021-07-31

### Added
- The library makes its triumphant return! 
  - Book moderation is now easier than ever, with support for reporting books to administrators.

---

## [0.11.01] - 2021-05-27

### Fixed
- An issue with some tickets potentially being viewable by people who weren't supposed to be able to see them
  - Some tickets in-game get `0` set as their round ID, so those tickets are now excluded from ticket queries.

---

## [0.11.0] - 2021-05-15

### Added
- A new round listing page. This is still a heavy WIP.


---

## [0.10.1] - 2021-05-12

### Changed
- Tweaked the display of the citations listing table and single citation view

---

## [0.10.0] - 2021-05-12

### Added
- Security citation viewer! It's fun! 

### Fixed 
- Ahelp feed sound not playing ([#10](https://github.com/nfreader/banbus/issues/10))

---

## [0.9.0] - 2021-04-28

### Added
- Admin play time tracking
- Permissions readout on player pages under tgdb
- If the target ckey's playtime is logged when a note is issued, that number now shows up on the note.

---

## [0.8.2] - 2021-04-02

### Fixed
- Made medium severity notes more distinctive

---

## [0.8.1] - 2021-03-08

### Added
- Feedback link updating for admins in TGDB

---

## [0.8.0] - 2021-03-08

### Added
- Preliminary new stat pages!

### Changed
- Added some HTML sanitization in a few places where user generated content hit the browser. 
- A lot of behind the scenes stuff for how the CSS and javascript files are built
- Direct links to invididual ticket actions (click on the ticket heading)

---

## [0.7.2] - 2021-03-05

### Changed
- Live ticket feed filtering is now done client-side, and is more robust, allowing admins to better specify which servers to poll from.

---

## [0.7.1] - 2021-03-04

### Added
- Live ticket feed for admins can now filter for ticket actions from a specific server

---

## [0.7.0] - 2021-03-01

### Added
- Content-ful popovers for round links and user badges (but only for admins, sorry!)
  - This called for a lot of additional functionality as well
- Added support for Patreon links for reasons.

---

## [0.6.0] - 2021-02-27

### Added
- Players can now mark tickets as public or private!
  - Public tickets are only visible at a uniquely generated URL
  - A ticket can be made public if you are the recipient of a ticket started by an admin OR if you open a ticket to an admin. 
  - Administrators can NOT change ticket publicity status

---

## [0.5.10] - 2021-02-26

### Added
- Messages can now be viewed and linked to, instead of just viewing them in a list

---

## [0.5.9] - 2021-02-24

### Added
- Messages for players and admins in TGDB
- A better popover script (hover over a relative timestamp like '8 Hours Ago')

---

## [0.5.8] - 2021-02-23

### Added
- Ban appeals template now links the round ID, added version number to the made by banbus footer

### Fixed 
- Tickets weren't displaying properly on small screens

---

## [0.5.7] - 2021-02-22

### Added 
- A mediaquery based dark mode!
  - Dark mode will respect your devices dark mode setting.

---

## [0.5.6] - 2021-02-22

### Added 
- The changelog is now available on the site
- A privacy policy
- Additional templates support for displaying pages without a header, and narrower pages

### Changed
- Game server links on the live ticket feed now link to the game servers
- Application sessions now live for a day, instead of closing when you leave the site

---

## [0.5.5] - 2021-02-19

### Changed
- Various refinements to the live ticket feed. I wish players could see this, it's so cool.
- The tgdb header now has a ckey search form on all pages

---

## [0.5.4] - 2021-02-18

### Changed
- The admin ticket feed is now a vue application!

---

## [0.5.3] - 2021-02-18

### Changed
- Consistent header across all application modules
- Better redirection after authentication

---

## [0.5.2] - 2021-02-16

### Added

- Admin rank change log

### Changed

- TGDB Player pages have more information and scaffolding for later functionality
- Ticket listings now have IDs set, so you can highlight an individual ticket on the listing

---

## [0.5.1] - 2021-02-15

### Fixed

- Ticket pages without any results weren't being properly handled, this has been fixed.

---

## [0.5.0] - 2021-02-15

### Added

- TGDB scaffold with admin links to tickets by ckey, an oft-requested feature

---

## [0.4.0] - 2021-02-11

### Added

- Infobus, with information about the current admin roster

---

## [0.3.1] - 2021-02-09

### Added

- A link to authenticate on 403 errors.

---

## [0.3.0] - 2021-02-07

### Added

- Tickets! Logged in players can now see tickets (ahelps) that they started, or that were sent to them.
- Timestamps are now dynamically converted to relative time (e.g. "six months ago") on the front end.

---

## [0.2.4] - 2021-02-03

### Added

- Rank icons and color functionality, will be deployed on bans shortly.

### Fixed

- Non-existent bans now correctly return a 404 error.

---

## [0.2.3] - 2021-02-03

### Added

- A small disclaimer to the end of the appeal form, indicating that the appeal was generated with Banbus

### Changed

- Groups bans that target multiple roles into one view

---

## [0.2.2] - 2021-02-02

### Fixed

- Links to log files weren't comprehensive enough, and didn't account for midnight rollovers.

---

## [0.2.1] - 2021-02-02

### Added

- Bans now show information about the server the ban was placed on

---

## [0.2.0] - 2021-02-01

### Added

- Disclaimers about what is/is not shown

### Changed

- Default content shown based on which modules are enabled/disabled.
- Session cookie name: `app` > `banbus`

---

## [0.1.0] - 2021-02-01

Initial release
