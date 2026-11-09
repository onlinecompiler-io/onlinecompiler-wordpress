=== OnlineCompiler.io Code Runner ===
Contributors: onlinecompiler
Tags: code, compiler, programming, education, code editor
Requires at least: 6.3
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Code examples your readers can edit and run in the page, in Python, C++, Java, SQL and dozens more languages.

== Description ==

Turn any code example into one your readers can run. Add a **Code runner** block (or the `[onlinecompiler]` shortcode in the classic editor), write the code, and readers get an editor with a Run button right in your post. They can change the code and its input and run it again, with no account and nothing to install.

* **Dozens of languages:** Python, C, C++, Java, JavaScript, TypeScript, Go, Rust, PHP, Ruby, C#, Kotlin, Swift, SQL (SQLite and DuckDB), R, Bash and more.
* **Runs in a sandbox, not on your server.** Code runs on OnlineCompiler.io in an isolated sandbox with no network access, so your site does no extra work.
* **Standard input:** give the program its input, and readers can change it.
* **Stored problems:** show a problem from your OnlineCompiler.io account, with its statement and tests, and readers judge their code against it.
* **Read-only examples, themes and heights:** light, dark or the reader's system setting; the editor fits the code or takes the height you set.
* **Buttons and messages in English, Portuguese or Spanish.**
* **Indexable:** the code is in the page as text before the editor loads.

The widget, its options and its JavaScript API are documented at [onlinecompiler.io/docs/widget](https://onlinecompiler.io/docs/widget). The plugin's source is on [GitHub](https://github.com/onlinecompiler-io/onlinecompiler-wordpress).

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

= Is it free? =

The plugin is free. Running code uses your OnlineCompiler.io account, which has a free plan with no credit card; see [pricing](https://onlinecompiler.io/pricing) for its monthly runs and the paid plans.

= Does the code run on my server? =

No. The code runs on OnlineCompiler.io, in a fresh sandbox for each run with no network access, and the sandbox is thrown away afterwards.

= Is the widget key secret? =

No. Widget keys are made to sit in public pages: they can run code in the widget but can't call the REST API. Set the key's allowed domain to your site so other sites can't use it.

= Can readers be graded? =

Yes. Store a problem with tests in your OnlineCompiler.io account and put its id in the block's Problem setting. Readers see the statement and judge their code against the tests. Tests on the page, without a stored problem, are described in the [widget documentation](https://onlinecompiler.io/docs/widget).

== External services ==

This plugin connects to OnlineCompiler.io, a code execution service run by RunCode Inc., to run the code in your code runners. Without it the code can't run.

* **On pages with a code runner**, the reader's browser loads the widget script from `https://onlinecompiler.io/widget.js`. When the code runner scrolls into view, the widget connects to `api.onlinecompiler.io`, sending your widget key; the browser adds your site's address (its origin), which is how the key's allowed domain is checked.
* **When a reader presses Run or Judge**, the widget sends the code, its input and the language to `api.onlinecompiler.io`, and shows the result. With a stored problem, the widget also loads the problem's statement and visible tests from there.
* As with any website, the service sees the reader's IP address. Readers don't sign in and the plugin sends no other data about them.

OnlineCompiler.io [terms of service](https://onlinecompiler.io/terms) and [privacy policy](https://onlinecompiler.io/privacy).

== Screenshots ==

1. A Code runner on a post: readers edit the code and press Run.
2. The Code runner block in the editor, with its settings.

== Changelog ==

= 0.1.0 =
* First release: the Code runner block, the `[onlinecompiler]` shortcode and the settings page.
