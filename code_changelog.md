# Changelog

Notable changes to the codebase from version to version. Mostly useful to myself, but hey if you find it interesting, that's great!

---

## [0.3.1] - 2021-02-09 • nfreader

- The Responder now adds the response code to the twig template. There's probably a better way to do this, but that can be refactored later
- The Twig error template will now attempt to load a corresponding error code component template with more information and some useful links.

---

## [0.3.0] - 2021-02-09 • nfreader

- Database repository refactored:

  - Data is fetched into a property of the repository class along with getters and setters.
  - This allows me to do things like paginate and pass it on in the service class
  - The repository also used to handle parsing the query data and further manipulating it. I moved this all into the service class instead, which is much cleaner. This also means that the repository doesn't do ANYTIHNG except query the database and return data.
  - I still need to do this with the Ban domain

- There's a Twig macro now for the user badges (where you see an admin's rank color + icon).
  - I can pass the entire User class into the macro and it spits out the HTML. I need to go through and add some defaults though.
