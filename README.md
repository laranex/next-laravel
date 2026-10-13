# Next Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/laranex/next-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/next-laravel)
[![Tests](https://github.com/laranex/next-laravel/actions/workflows/tests.yml/badge.svg)](https://github.com/laranex/next-laravel/actions/workflows/tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/laranex/next-laravel.svg?style=flat-square)](https://packagist.org/packages/laranex/next-laravel)
[![License](https://img.shields.io/packagist/l/laranex/next-laravel.svg?style=flat-square)](LICENSE.md)

Modular architecture for Laravel: self-contained modules with routes, controllers, requests, features, operations and jobs, with generators for each. Built for humans and AI agents.

As a Laravel app grows, controllers tend to collect validation, queries, mail and queue calls in one place. Next Laravel splits every request into small units with one responsibility each: a controller serves a feature, the feature runs jobs and operations, and jobs do the work. Every unit lives inside its module, so one folder holds everything a part of your app needs.

## Why Next Laravel

- **One class, one responsibility.** Controllers, features, operations, requests and jobs each do one thing, so code is easy to find, read and test.
- **Self-contained modules.** Apart from route files, every unit lives in its module under `app/Modules/{Name}Module`: controllers, requests, features, operations and jobs.
- **A generator for every unit.** `next:route`, `next:controller`, `next:request`, `next:feature`, `next:operation` and `next:job`, with stubs you can publish and change.
- **Run units by instance or by class name.** `run(StoreBlogJob::class, ['payload' => $data])` builds the unit with positional or named arguments, and only features, operations and jobs are accepted.
- **Safe generators.** Every name is checked before a file is written, so a name can never inject code or write outside its directory.
- **Route files that load themselves.** Every file under `routes/web` and `routes/api` is loaded with its middleware group and prefix, and nothing is scanned once routes are cached.
- **Queues built in.** Extend `QueueableJob` and call `runInQueue()`, on the default queue or a named one.
- **Plain Laravel underneath.** Units are dispatched through Laravel's bus and requests are form requests, so there is nothing new to learn.

Prefer jobs and requests in domains that every module shares? See [Better Laravel](https://laranex.vercel.app/better-laravel/introduction).

## Installation

Requires PHP 8.1+ and Laravel 10 (10.48.29 or later), 11, 12 or 13.

```bash
composer require laranex/next-laravel
```

## Documentation

The full documentation lives at **[laranex.vercel.app/next-laravel](https://laranex.vercel.app/next-laravel)**:

- [Next Laravel introduction](https://laranex.vercel.app/next-laravel/introduction): why use it and what it does
- [Next Laravel principles](https://laranex.vercel.app/next-laravel/principles): the request lifecycle and the responsibility of each unit
- [Installing Next Laravel](https://laranex.vercel.app/next-laravel/installation): Composer, the generator commands, naming rules and the facade
- [Next Laravel AI agent skill](https://laranex.vercel.app/next-laravel/ai-agent-skill): the skill for Claude Code, Codex, Cursor and other coding agents
- [Next Laravel configuration](https://laranex.vercel.app/next-laravel/configuration): route loading, route prefixes, views and stubs
- [Upgrading Next Laravel](https://laranex.vercel.app/next-laravel/upgrading): from v1.1 to v4
- [Generating routes](https://laranex.vercel.app/next-laravel/usage/route): route files loaded from `routes/web` and `routes/api`
- [Generating controllers](https://laranex.vercel.app/next-laravel/usage/controller): controllers that serve features
- [Generating features](https://laranex.vercel.app/next-laravel/usage/feature): features that validate requests and run jobs and operations
- [Generating requests](https://laranex.vercel.app/next-laravel/usage/request): form requests that validate and authorize
- [Generating operations](https://laranex.vercel.app/next-laravel/usage/operation): reusable groups of jobs
- [Generating jobs](https://laranex.vercel.app/next-laravel/usage/job): synchronous and queueable jobs

## License

MIT. See [LICENSE.md](LICENSE.md).
