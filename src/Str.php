<?php

declare(strict_types=1);

namespace Laranex\NextLaravel;

use Illuminate\Support\Str as LaravelStr;

class Str extends LaravelStr
{
    /**
     * Convert a value to a pluralized kebab-case route name.
     */
    public static function route(string $value): string
    {
        return self::kebab(self::plural($value));
    }

    /**
     * Convert a value to a directory name.
     */
    public static function directory(string $value): string
    {
        return self::lower($value);
    }

    /**
     * Determine the real name of the given name, excluding the given pattern.
     *
     *    i.e. the name "CreateArticleFeature.php" with pattern '/Feature.php/'
     *    will result in "Create Article".
     */
    public static function realName(string $name, string $pattern = '//'): string
    {
        $name = (string) preg_replace($pattern, '', $name);

        $parts = preg_split('/(?=[A-Z])/', $name, -1, PREG_SPLIT_NO_EMPTY);

        return implode(' ', $parts === false ? [] : $parts);
    }

    /**
     * Get the given name formatted as a feature.
     *
     *    i.e. "Create Post Feature", "CreatePostFeature.php", "createPost", "create"
     *    and many other forms will be transformed to "CreatePostFeature" which is
     *    the standard feature class name.
     */
    public static function feature(string $name): string
    {
        $parts = array_map(fn (string $part): string => self::studly($part), explode('/', $name));
        $feature = self::studly(self::stripSuffix((string) array_pop($parts), 'Feature').'Feature');

        $parts[] = $feature;

        return implode(DIRECTORY_SEPARATOR, $parts);
    }

    /**
     * Get the given name formatted as a job.
     *
     *    i.e. "Send Email Job", "SendEmailJob.php", "sendEmail"
     *    and many other forms will be transformed to "SendEmailJob" which is
     *    the standard job class name.
     */
    public static function job(string $name): string
    {
        return self::studly(self::stripSuffix($name, 'Job').'Job');
    }

    /**
     * Get the given name formatted as an operation.
     *
     *    i.e. "Create Post Operation", "CreatePostOperation.php", "createPost"
     *    and many other forms will be transformed to "CreatePostOperation" which is
     *    the standard operation class name.
     */
    public static function operation(string $name): string
    {
        return self::studly(self::stripSuffix($name, 'Operation').'Operation');
    }

    /**
     * Get the given name formatted as a module name.
     *
     *    i.e. "blog", "Blog", "BlogModule" are all transformed to "BlogModule".
     */
    public static function module(string $name): string
    {
        $normalized = self::studly($name);

        if (! str_ends_with($normalized, 'Module')) {
            $normalized .= 'Module';
        }

        return $normalized;
    }

    /**
     * Get the given name formatted as a controller name.
     */
    public static function controller(string $name): string
    {
        return self::studly(self::stripSuffix($name, 'Controller').'Controller');
    }

    /**
     * Get the given name formatted as a model.
     *
     * Model names are just StudlyCase.
     */
    public static function model(string $name): string
    {
        return self::studly($name);
    }

    /**
     * Get the given name formatted as a policy.
     */
    public static function policy(string $name): string
    {
        return self::studly(self::stripSuffix($name, 'Policy').'Policy');
    }

    /**
     * Get the given name formatted as a request.
     *
     *    i.e. "StorePostRequest.php", "storePost"
     *    and many other forms will be transformed to "StorePostRequest" which is
     *    the standard request class name.
     */
    public static function request(string $name): string
    {
        return self::studly(self::stripSuffix($name, 'Request').'Request');
    }

    /**
     * Remove a trailing suffix (with an optional ".php") from the name.
     */
    private static function stripSuffix(string $name, string $suffix): string
    {
        return (string) preg_replace('/'.$suffix.'(\.php)?$/', '', $name);
    }
}
