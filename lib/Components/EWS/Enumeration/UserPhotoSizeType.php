<?php
//declare(strict_types=1);

/**
* @copyright Copyright (c) 2016 James I. Armes http://jamesarmes.com/
*
* @author James I. Armes http://jamesarmes.com/
*
* @license AGPL-3.0-or-later
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Affero General Public License as
* published by the Free Software Foundation, either version 3 of the
* License, or (at your option) any later version.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU Affero General Public License for more details.
*
* You should have received a copy of the GNU Affero General Public License
* along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*/

namespace OCA\EWS\Components\EWS\Enumeration;

use OCA\EWS\Components\EWS\Enumeration;

/**
 * Defines the size of a user's photo being requested.
 *
 * @package OCA\EWS\Components\EWS\Enumeration
 */
class UserPhotoSizeType extends Enumeration
{
    /**
     * The image is 48 pixels high and 48 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR48X48 = 'HR48x48';

    /**
     * The image is 64 pixels high and 64 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR64X64 = 'HR64x64';

    /**
     * The image is 96 pixels high and 96 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR96X96 = 'HR96x96';

    /**
     * The image is 120 pixels high and 120 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR120X120 = 'HR120x120';

    /**
     * The image is 240 pixels high and 240 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR240X240 = 'HR240x240';

    /**
     * The image is 360 pixels high and 360 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR360X360 = 'HR360x360';

    /**
     * The image is 432 pixels high and 432 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR432X432 = 'HR432x432';

    /**
     * The image is 504 pixels high and 504 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR504X504 = 'HR504x504';

    /**
     * The image is 648 pixels high and 648 pixels wide.
     *
     * @since Exchange 2013
     *
     * @var string
     */
    const HR648X648 = 'HR648x648';

    /**
     * The image is 1024 pixels high and 1024 pixels wide.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    const HR1024XN = 'HR1024xN';

    /**
     * The image is 1920 pixels high and 1920 pixels wide.
     *
     * @since Exchange 2016
     *
     * @var string
     */
    const HR1920XN = 'HR1920xN';
}
