[33m99262f0[m[33m ([m[1;36mHEAD -> [m[1;32mdev_project_documentation[m[33m, [m[1;31morigin/dev_project_documentation[m[33m)[m Reviewed, optimized a bit. Corrected typos
[33mb9a86d7[m Corrected syntax
[33m6ae33e7[m Updated README.md
[33m6956a7c[m Added CONTRIBUTING.md
[33m2247156[m Added Authentication documentation
[33m55e2449[m Added tests on entities. Finalized unit and functional tests. This closes #6.
[33maecf1f4[m Corrected a method name
[33m5233fbc[m Added test for redirect to target path after login
[33m41e7f7c[m Added invalid CSRF Token test
[33mbe6dc5d[m Added tests for UserController
[33mc35bdd7[m Changed tests directory whitelist in order to test only important things
[33mf3605f3[m Removed unused import
[33m70d6c70[m Grabbed correct file from dev branch
[33m829c6a0[m Added bad username and wrong credentials tests
[33mab167cb[m Created SecurityController functional tests
[33m2dbc293[m Removed action logout in SecurityController and setted the route in config/routes.yaml as nothing was done in the controller action before.
[33md36a58a[m Removed whiteline
[33mf667adc[m Removed unused variables
[33m2d41bc2[m Finalized TaskController's tests
[33m36a1370[m Pursued functional tests on TaskControllerTest (testEdit, testEditNotByOwner, testToggle, testDelete) Added another user on UserFixtures so that we can test more things like (testEditNotByOwner) Fixtures are now loaded each time for each test, have to find a way to improve that like client initialization.
[33m4a17b44[m Installed LiipFunctionalTestBundle in order to load fixtures easily during tests
[33m31c9ed7[m Started creating functional tests for the project (i know this is a big commit :x)
[33m15311c7[m Corrected small visual problem (navbar collapse border color was white, too flashy)
[33m221e4e3[m Made the project responsive Finalized project style Closes #5.
[33m99b3287[m Added username display
[33mf5b1fc9[m Added custom error pages
[33me86f2e6[m Pursued frontend styling, added view completed tasks action
[33m1b7edfc[m Pursued frontend styling
[33m8c1aaf6[m Added pagination (on tasks by now) and twig intl extension to have date in fr format
[33m844d83f[m Pursed frontend styling
[33m59a43d7[m Added FontAwesome 5.5 and some icons
[33me6128df[m Pursued frontend optimizations + bug corrections on UserVoter (typo) not allowing admin access to user list
[33mc6066fd[m Started optimizing frontend
[33m266b7f9[m Corrected access denied messages
[33m9909147[m Added authorizations requirements on tasks management actions (edit, delete)
[33m2eda49f[m Added authorizations requirements on users managements actions (list, create, edit)
[33m37127c5[m Corrected edit button's label
[33m6dc841c[m Added users' roles selection in user form
[33m6c9e1c1[m Added RolesType so that we can select Roles in User's Form
[33m4ef2c50[m Added Roles Hierarchy
[33m0faa0c0[m Added User's roles attribute
[33m29fae62[m Removed 'Action' suffixes from all controller's method (not required in Symfony 4.* anymore)'
[33mc3f690d[m Corrected an error (form->isValid() before form->isSubmitted()). Optimized way to get users' repo in listAction
[33ma768d13[m Tasks now have an author after creation (current authenticated user). Created a TaskEditType (show Task's author username while editing [for information only, task's author cannot be changed as required])
[33m6684bd0[m Corrected .gitignore
[33m7c9e0d4[m Added Task's author attribute
[33mf6f95c2[m Corrected again, yeah I like README.mdgit add -A !
[33m34c5498[m Added Codacy and CodeClimate badges
[33m55b8053[m Corrected syntax... again
[33ma505950[m Used minified version of boostrap
[33m48a7e76[m Corrected syntax
[33m94ae06f[m Corrected syntax
[33m19ea7b1[m Changed default_locale to fr
[33m6c82493[m Removed thoses files as it is not used anymore in Symfony 4
[33m3d4cf2a[m Corrected syntax
[33me90909f[m Project upgraded to Symfony 4.1.7, file and directory structure modified as required by Symfony Flex. Closes #1
[33mc99bbfe[m Prepared .gitignore and .gitattributes for Symfony 4.1
[33m55ce2b8[m Removed deprecations (prepared migration from 3.4 to 4.1)
[33mdfa25e6[m Upgraded from Symfony 3.1 to 3.4.18
[33m00c008f[m Installed project MVP
[33m218ea93[m Added .gitignore and .gitattributes.
[33m2853b60[m Corrected
[33med1dee1[m first commit
