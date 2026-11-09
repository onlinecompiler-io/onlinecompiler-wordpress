=== OnlineCompiler.io Code Runner: Online Compiler and Runnable Code Blocks ===
Contributors: onlinecompiler
Tags: code block, compiler, python, programming, education
Requires at least: 6.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add an online compiler to your posts: runnable code blocks readers edit and run in Python, C++, Java, JavaScript, SQL and 46 languages in all.

== Description ==

**OnlineCompiler.io Code Runner adds an online compiler to WordPress.** Put a **Code runner** block in a post or page (or the `[onlinecompiler]` shortcode in the classic editor), write the code, and readers get a code editor with a Run button right in the page. They can change the code and its input and run it again, with no account and nothing to install. Syntax highlighters only show code; this one runs it.

The code runs on [OnlineCompiler.io](https://onlinecompiler.io), a code execution service, in an isolated sandbox with no network access. Nothing runs on your WordPress server.

= Who it's for =

* **Programming tutorials and blogs:** every example in a post becomes one readers can try and change.
* **Online courses and coding lessons:** students practise in the lesson itself instead of installing a compiler.
* **Teachers and schools:** exercises with tests that students judge their code against.
* **Documentation:** runnable examples for an API, library or language.

= Features =

* **46 languages**, with the code highlighted as readers type.
* **Runs in a sandbox, not on your server:** a fresh sandbox for each run, no network access, thrown away afterwards.
* **Standard input:** give the program its input; readers can change it.
* **Graded exercises:** show a problem stored in your OnlineCompiler.io account, with its statement and tests, and readers judge their code against it.
* **Read-only examples, themes and heights:** light, dark or the reader's system setting; the editor fits the code or takes the height you set.
* **Buttons and messages in English, Portuguese or Spanish.**
* **Search-friendly:** the code is in the page as text before the editor loads, so search engines index it.
* **Light on your site:** the widget script loads only on pages with a Code runner, deferred, and connects only when the runner scrolls into view.

= Supported languages =

Python, C, C++ (and C++20), Java, JavaScript (Node.js), TypeScript (Deno), C#, F#, Visual Basic, Go, Rust, PHP, Ruby, Kotlin, Swift, Dart, Scala, Groovy, Clojure, Haskell, OCaml, Elixir, Erlang, Racket, Common Lisp, Prolog, R, Julia, Octave, Fortran, COBOL, Pascal, Ada, D, Nim, Zig, Crystal, Perl, Lua, Bash, PowerShell, Objective-C, FreeBASIC, Assembly (NASM), SQL (SQLite) and DuckDB. Python includes NumPy, pandas, SciPy, scikit-learn and Matplotlib.

The widget, its options and its JavaScript API are documented at [onlinecompiler.io/docs/widget](https://onlinecompiler.io/docs/widget). The plugin's source code is on [GitHub](https://github.com/onlinecompiler-io/onlinecompiler-wordpress).

= Shortcode =

`[onlinecompiler lang="python"]print("Hello")[/onlinecompiler]`

Attributes, all optional:

* `lang`: a compiler id such as `python-3.14` or `g++-15`, or a short name such as `python`, `cpp`, `java`, `js` or `sql`. The site default if left out.
* `stdin`: the program's starting input. Use `\n` for a new line: `stdin="3\n4"`.
* `theme`: `auto`, `light` or `dark`.
* `height`: the editor's height in pixels.
* `readonly`: `1` so readers can run the code but not change it.
* `lang_select`: `true` or `false` to show or hide the language menu.
* `problem`: the id of a problem stored in your account.
* `locale`: `pt` or `es` for the buttons and messages.

For code full of `<` and `&` (C++ includes, HTML), the block is the safer choice: the classic editor can change such code when it is saved.

== Installation ==

1. Install and activate the plugin.
2. Create a free account at [onlinecompiler.io](https://onlinecompiler.io) (sign in with Google), open the **Widgets** page of the dashboard and create a widget key. Set its allowed domain to your site's domain, so only your site can use it.
3. In WordPress, go to **Settings, OnlineCompiler.io** and paste the widget key.
4. Add a **Code runner** block to a post or page.

== Frequently Asked Questions ==

= How do I add an online compiler to WordPress? =

Install this plugin, paste your free OnlineCompiler.io widget key in Settings, OnlineCompiler.io, and add a Code runner block to any post or page. Write the starting code in the block and choose its language in the sidebar. Readers can then edit and run it in the page.

= How do I let readers run Python code in a WordPress post? =

Add a Code runner block and leave the language at Python (the default), or use `[onlinecompiler lang="python"]print("Hello")[/onlinecompiler]` in the classic editor. Readers press Run and see the output under the code.

= Is it free? =

The plugin is free. Running code uses your OnlineCompiler.io account, which has a free plan with no credit card; see [pricing](https://onlinecompiler.io/pricing) for its monthly runs and the paid plans.

= Do readers need an account? =

No. Readers edit and run the code without signing in. Only you, the site owner, need an OnlineCompiler.io account, for the widget key.

= Does the code run on my server? =

No. The code runs on OnlineCompiler.io, in a fresh sandbox for each run with no network access, and the sandbox is thrown away afterwards. Your server only serves the page.

= Does it work with the classic editor? =

Yes, with the `[onlinecompiler]` shortcode; its attributes are listed above. For code full of `<` and `&` (C++ includes, HTML), use the block: the classic editor can change such code when it saves.

= Does it slow down my site? =

The widget script loads only on pages that have a Code runner, and deferred, so it doesn't hold up the page. Each runner connects only when it scrolls into view.

= What if the service can't be reached? =

The code is in the page as text, so readers still see it. Only the Run button needs OnlineCompiler.io.

= Can students' code be graded? =

Yes. Store a problem with tests in your OnlineCompiler.io account and put its id in the block's Problem setting. Readers see the statement and judge their code against the tests, including hidden tests whose input and expected output they never see. Tests written on the page, without a stored problem, are described in the [widget documentation](https://onlinecompiler.io/docs/widget).

= Is the widget key secret? =

No. Widget keys are made to sit in public pages: they can run code in the widget but can't call the REST API. Set the key's allowed domain to your site so other sites can't use it.

== External services ==

This plugin connects to OnlineCompiler.io, a code execution service run by RunCode Inc., to run the code in your code runners. Without it the code can't run.

* **On pages with a code runner**, the reader's browser loads the widget script from `https://onlinecompiler.io/widget.js`. When the code runner scrolls into view, the widget connects to `api.onlinecompiler.io`, sending your widget key; the browser adds your site's address (its origin), which is how the key's allowed domain is checked.
* **When a reader presses Run or Judge**, the widget sends the code, its input and the language to `api.onlinecompiler.io`, and shows the result. With a stored problem, the widget also loads the problem's statement and visible tests from there.
* As with any website, the service sees the reader's IP address. Readers don't sign in and the plugin sends no other data about them.

OnlineCompiler.io [terms of service](https://onlinecompiler.io/terms) and [privacy policy](https://onlinecompiler.io/privacy).

== Screenshots ==

1. A runnable Python example on a post: the reader pressed Run and the output shows under the code.
2. The Code runner block in the block editor, with the language, theme and height settings in the sidebar.

== Changelog ==

= 0.1.0 =
* First release: the Code runner block, the `[onlinecompiler]` shortcode and the settings page.
