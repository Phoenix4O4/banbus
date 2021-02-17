# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project loosely adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## Upcoming

### Added

- Flag your tickets as public and generate unique links to them

---

## [0.5.2] - 2021-02-16 • nfreader

### Added

- Admin rank change log

### Changed

- TGDB Player pages have more information and scaffolding for later functionality
- Ticket listings now have IDs set, so you can highlight an individual ticket on the listing

---

## [0.5.1] - 2021-02-15 • nfreader

### Fixed

- Ticket pages without any results weren't being properly handled, this has been fixed.

---

## [0.5.0] - 2021-02-15 • nfreader

### Added

- TGDB scaffold with admin links to tickets by ckey, an oft-requested feature

---

## [0.4.0] - 2021-02-11 • nfreader

### Added

- Infobus, with information about the current admin roster

---

## [0.3.1] - 2021-02-09 • nfreader

### Added

- A link to authenticate on 403 errors.

---

## [0.3.0] - 2021-02-07 • nfreader

### Added

- Tickets! Logged in players can now see tickets (ahelps) that they started, or that were sent to them.
- Timestamps are now dynamically converted to relative time (e.g. "six months ago") on the front end.

---

## [0.2.4] - 2021-02-03 • nfreader

### Added

- Rank icons and color functionality, will be deployed on bans shortly.

### Fixed

- Non-existent bans now correctly return a 404 error.

---

## [0.2.3] - 2021-02-03 • nfreader

### Added

- A small disclaimer to the end of the appeal form, indicating that the appeal was generated with Banbus

### Changed

- Groups bans that target multiple roles into one view

---

## [0.2.2] - 2021-02-02 • nfreader

### Fixed

- Links to log files weren't comprehensive enough, and didn't account for midnight rollovers.

---

## [0.2.1] - 2021-02-02 • nfreader

### Added

- Bans now show information about the server the ban was placed on

---

## [0.2.0] - 2021-02-01 • nfreader

### Added

- Disclaimers about what is/is not shown

### Changed

- Default content shown based on which modules are enabled/disabled.
- Session cookie name: `app` > `banbus`

---

## [0.1.0] - 2021-02-01 • nfreader

Initial release
