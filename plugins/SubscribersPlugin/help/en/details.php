<?php
/**
 * SubscribersPlugin for phplist
 * 
 * This file is a part of SubscribersPlugin.
 *
 * SubscribersPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * SubscribersPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 * @author    Duncan Cameron
 * @copyright 2011-2013 Duncan Cameron
 * @license   http://www.gnu.org/licenses/gpl.html GNU General Public License, Version 3
 */

/**
 * Help text
 * 
 * @category  phplist
 * @package   SubscribersPlugin
 */
?>
<p>The page displays the status and attributes for each subscriber.</p>
<p>You can filter the results by 
<ul>
<li>searching on the subscriber's email address, id or unique id, or the value of an attribute,
<li>subscribers who belong to a specific list,
<li>restricting to confirmed subscribers or unconfirmed subscribers,
<li>restricting to blacklisted subscribers or not blacklisted subscribers.
</ul>
</p>
<p>Attributes can be displayed as separate columns in the listing.
The form shows only the first 15 attributes, with the attribute name truncated to 20 characters.</p>
<p>If no attributes have been created then the selection form is not displayed.</p>
<table>
<tr>
<td>Export</td>
<td>The fields displayed can be exported as a CSV file.</td>
</tr>
<tr>
<td>email</td>
<td>The email address of the subscriber, which is a link to the Subscriber Details page.</td>
</tr>
<tr>
<td>Confirmed</td>
<td>An icon is displayed if the email address is unconfirmed.</td>
</tr>
<tr>
<td>Blacklisted</td>
<td>An icon is displayed if the subscriber is blacklisted.</td>
</tr>
<tr>
<td>Attributes</td>
<td>Each attribute that has been selected is displayed in a separate column.</td>
</tr>
<tr>
<td>HTML</td>
<td>Whether the subscriber should be sent HTML format emails.</td>
</tr>
<tr>
<td>Lists</td>
<td>The number of lists to which the subscriber belongs.</td>
</tr>
<tr>
<td>Lists</td>
<td>The number of campaigns that the subscriber has opened.</td>
</tr>
<tr>
<td>Lists</td>
<td>The number of campaigns of which the subscriber has clicked at least one link.</td>
</tr>
</table>
