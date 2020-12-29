# 8fold Laravel DMS Universals

> WARNING: This library is primarily used for 8fold-specific projects (indicated by the `ef` prefix).

A library for working with the flat-file [.DMS](Document Management System) used on most 8fold and 8fold-client sites using Laravel.

The fundamental philosophy is that flat-files (usually markdown) primarily store content with minimal metadata while traditional database primarily store metadata with minimal content (specifically relationships).

Because we use a flat-file approach, folder stucture is important; however, the root file system path can be overridden in most cases.

## Installation

```bash
composer require 8fold/ef-dms-laravel-universals
```

## Usage

> Core principle: The URL is key.

We divide content into three major groups:

1. text-based,
2. multimedia, and
3. site assets.

Note: If it is not stated as a requirement, chances are whatever it is in the examples, is optional.

The following is an example content folder structure:

```bash
- /content-folder
  - /.assets
    - /.favicons
      - favicon.ico
  - /.media
  	- /.images
      - poster.png
    - /path
      - /to
        - /sub-url
          - poster.png
  - content.md
```

The content folder primarily holds text-based content. The folder structure matrches the URL paths available on the site. If a user puts in a URL and the path does not lead to directory with a `content.md` file, this will result in a `404 error`.

The `.assets` and `.media` folders both hold files included or linked to from the with the content pages.

The `.assets` folder is for more globally accessed files; favicons, ui accents, and the like. Each file is placed at the root of the a category folder within the `.assets` folder. For example, we want to get to a `ui` asset with the filename `filename.ext`; the URL to the individual asset would be `https://domain.com/assets/ui/filename.ext` - and the file path would be - `[assets root]/.assets/ui/filename.ext`

The `.media` folder is for files associated with one or more specific pages; embedded images, audio files, and so on. Each file is placed within a hidden folder named after the media type category, pluralized. For example, we want to get to an audio file for a page with the URL `https://domain.com/media/audios/hello/world/filename.mp4` - and the file path would be - `[media root]/hello/world/.audios/filename.mp4`

Note: "audios" is apparently [an acceptable pluralized version](https://www.wordhippo.com/what-is/the-plural-of/audio.html) of "audio" in this context as we are using it as shorthand for "a collection of multiple audio files." So, `audio/mp4` should be stored in `[media root]/[url path]/.audios.

There are two required route prefixes for accessing files:

- assets
- media

This separates text from non-text content, maintains a mirrored folder structure for easier navigation by the user, and allows users to have a URL path that includes words like "applications" or "media" or "images."

A sample Laravel provider and content folder are available in the `tests` folder.

### Site tracking

> If you choose to use the site tracking solution offered by the DMS, you agree to absolve 8fold and its developers of all responsibility and liability related to compliance with applicable data privacy laws.

With that out of the way, the site tracker:

- DOES NOT store personal information of the user.
- DOES store certain aspects of server state and client request:
	- current server date and time in [.UTC](universal time code),
	- current requested path, and
	- previously requested path.
- DOES use standard list of automated users (bots) to differentiate human users from computer users.
- WILL hash (scramble) an unique identifier provided by the user of the script to further conceal the identity of the human user, their client information, and their user agent.

It is recommended that the unique identifier passed to the site tracker is a server-generated session identifier modified with [salt](https://en.wikipedia.org/wiki/Salt_(cryptography)) and [pepper](https://en.wikipedia.org/wiki/Pepper_(cryptography)). To distinguish the session id passed to the site tracker from the one stored on the client and server to identify the same user across multiple pages.

## Details

It's helps with:

- title generation,
- social media sharing metadata and tags, and
- nudging creators to create assets for a full web experience.

### 8fold Design System

Seems there are so many design systems out there - and we have ours.

For this dicussion design systems off two primary benefits:

1. Design decisions made once.
2. Change one thing, in one place, to update as many things in as many places.

Given this package is meant primarily for use with 8fold sites. The design system pretty much doubles down on that. While we can use the server-side and HTML elements on clients sites, we typically only use the 8fold Design System for 8fold company sites.j

With that said, it's modular like most things we build; so, nothing really stopping your from using parts or all of it if you want to.

We partition the CSS conceptually into:

- Size,
- Appearance,
- Positioning, and
- Typography.

Size, appearance, and positioning are considered global as effects can be applied to almost any element. Typography, on the other hand, are CSS properties that affect text content only.

We restrict ourselves to work within a 9-point scale for most things to create a consistent naming convention:

- 100
- 200
- 300
- 400
- 500
- 600
- 700
- 800
- 900

Consider `font-weight` 400 is "normal" 700 is "bold" and 100 would be similar to "ultra-thin." Now consider a scale for a `border` where 100 is 1px, 200 is 2px, 300 is 3px, and so on. And, `font-size` where 100 is 1ex, 200 is 2ex, 300 is 4ex, 400 is 8ex, and so on. This gives us room to add something like 150 and increase gaps between values.

For certain sizing units, we tend to use `ex` where height is important and `ch` where width takes precendent; for example, line-height would use `ex` and the width of a container for text would be set using `ch`. We typically favor `rem` to establish a root size from which other sizes can be calculated relatively.

Our approach is mobile-first, which is a misnomer. Our approach is shortest viewing distance by default using screen resolution (width) as a way of inferring optimal viewing distance. So, the smaller the screen, the closer we presume you will be; therefore, by default, we start at the smallest sizes and work our way up from there. We prefer scaling over snapping and breaking.

## Other

{links or descriptions or license, versioning, and governance}
