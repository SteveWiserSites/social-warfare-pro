<?php

/*
 * This file is part of the Pinterest PHP library.
 *
 * (c) Hans Ott <hansott@hotmail.be>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.md.
 *
 * Source: https://github.com/hansott/pinterest-php
 */

namespace Pinterest\App;

/**
 * The possible application scopes.
 *
 * @author Hans Ott <hansott@hotmail.be>
 */
class Scope
{
    const READ_PUBLIC = 'read_public';
    const WRITE_PUBLIC = 'write_public';
    const READ_RELATIONSHIPS = 'read_relationships';
    const WRITE_RELATIONSHIPS = 'write_relationships';
}