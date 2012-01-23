=== Ad Rotator ===
Contributors: kpumuk
Tags: ads, advertisements, ad, widget, rotate, sidebar, adsense, clicksor, chitika
Requires at least: 2.8.0
Tested up to: 2.8.2
Stable tag: 2.0.3

Ad Rotator is a simple widget to display random HTML code (advertisements)
from a given group of HTML-chunks on sidebar.

== Description ==

Ad Rotator is a simple WordPress widget to display random HTML code
from a given group of HTML-chunks separated with `<!--more-->`
on sidebar. Basically it shows different HTML every time you requesting
page. There are infinite number of instances of this widget may exist.

= Support =

If you have any suggestions, found a bug, or just wanted to say "thank
you",– feel free to email me <a href="mailto:kpumuk@kpumuk.info">kpumuk@kpumuk.info</a>.
Promise, I will answer every email I received.

If you want to contribute your code, see the *Development* section under
the *Other Notes* tab.

= Migrating from AdRotator plugin =

<a href="http://blog.taragana.com/index.php/archive/wordpress-plugin-adrotator-rotate-your-ads-including-adsense-dynamically/">AdRotator</a>
plugin is a simple file-based ad rotation solution. It was developed by
Angsuman Chakraborty long time ago, but occasionally we have the same
plugin names from WordPress' point of view. If you are using this plugin,
you may click upgrade link from your *Plugins* page and it being replaced
with Ad Rotator widget.

So now you have two ways to solve the problem `'getad()' function is undefined`:
1. you can download AdRotator and ignore upgrade notices, or
2. you can upgrade your theme to use Ad Rotator Widget.

Here is how to upgrade your theme. Find all occurrences of `getad` function,
and replace them with something like this:

    register_sidebar(array(
      'name' => 'ad-area',
      'id' => 'ad-area',
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '',
      'after_title' => ''
    ));

Make sure you've replaced `ad-area` with the name suitable for you. Also
you can specify additional options, like `before_title` and `after_title`.

Then open the *Appearence/Widgets* page in *Site Admin* and configure
Ad Rotator widget instances for your advertisements area. Just take into
account, that in files for AdRotator ads are separated with new line
character (ie each line means separate ad), but in Ad Rotator widget
you should separate you blocks with `<!--more-->` (so each of them
may contain more then one line.)

Anyways, sorry for сonfusion, I did not want to сheat on you.

== Installation ==

1. Download and unpack plugin files to the `wp-content/plugins/ad-rotator`
   directory.
2. Enable **Ad Rotator** plugin on your *Plugins* page in
   *Site Admin*.
3. Go to the *Appearence/Widgets* page in *Site Admin* and drag as
   many Ad Rotator widgets to your sidebars as you wish. Configure
   instances separating HTML blocks with `<!--more-->`. Save changes.
4. Now on Ad Rotator blocks should appear on your sidebars.

== Frequently Asked Questions ==

= How to enter several ads to a single text box? =

Separate your ad blocks with `<!--more-->` sequence.

= How many ads every instance of widget could handle? =

Number of advertisements in each instance is unlimited.

= Can I use Google AdSense code as one of my ads? =

Of course, you can use any HTML you wish (AdSense, Clicksor, Chitika,
and everything else).

= WordPress shows `'getad()' function is undefined` error =

See explanation on the *Description* page in *Migrating from
AdRotator plugin* section.

== Screenshots ==

1. Ad Rotator widget configuration.
2. Sidebar with Ad Rotator widgets.

== Changelog ==

= 2.0.3 (July 30, 2009) =
* Fixed problem with backslashes appearing inside HTML (thanks to <a href="http://www.maxxkremer.com/">Maxx Kremer</a>).

= 2.0.2 (July 29, 2009) =
* Added section *Migrating from AdRotator plugin* in readme.txt.

= 2.0.1 (July 29, 2009) =
* Fixed Installation section in readme.txt.
* Filter out HTML when user has no rights to edit unfiltered HTML.

= 2.0.0 (July 29, 2009) =
* Completely rewritten using new WordPress 2.8 widgets API.
* Readme file and couple of screenshots added.

= 1.0.1 (March 31, 2007) =
* Plugin home page updated.

= 1.0.0 (May 1, 2006) =
* Initial plugin implementation.

== Development ==

Sources of this plugin are available both in SVN and Git:

* <a href="http://svn.wp-plugins.org/ad-rotator/">WordPress SVN repository</a>
* <a href="http://github.com/kpumuk/ad-rotator/">GitHub</a>

Feel free to check them out, make your changes and send me patches.
Promise, I will apply every patch (of course, if they add a value to the
product). Email for patches, suggestions, or bug reports:
<a href="mailto:kpumuk@kpumuk.info">kpumuk@kpumuk.info</a>.
