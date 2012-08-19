#
# Configuration file for Rad Upload Plus.
# Please note that there should not be any spaces before or after the '='
# sign. Lines beginning with the '#' symbol are comments and will not be
# processed.
#


#
# For backward compatibility with older versions, the url, message and
# max_upload properties can be defined in this file or in the html that contains
# the applet. The html setting has precedence.
#
# examples:
#   url=http://67.131.250.71/upload.php
#   message=http://www.radinks.com/upload/init.html
#
#   url=http://localhost/dnd/upload.php
#   message=http://www.radinks.com/upload/plus/init.html

message=http://www.pinmo.com.ar/backend/config/uploaderInitMsg.php
#message=http://localhost/devs/pinmo-2.com.ar/backend/config/uploaderInitMsg.php

#
# You can set the maximum total size (in kilobytes) of the upload with the
# max_upload parameter. A limit on each individual file can be imposed using
# the max_file property.
#

max_upload=51200
max_file=5120

#
# The max_upload_message is displayed when the max_upload size is exceeded.
# If you enter a text message here it will be displayed as a popup. If you
# enter a url the page you specify will be loaded inside the applet.
#
# example:
#     max_upload_message=http://www.radinks.com/upload/plus/exceed.html
#

max_upload_message=El tamaño máximo permitido es de 5120 KB (o 5 MB) por archivo.

#
# As the name suggests the full_path setting determines if absolute pathnames
# should be sent to the server. If you switch this off, folder information will
# be stripped from the filenames.
#

full_path=yes

#
# The next few lines configure client side filtering.
# enter a comma separated list of file extensions in the allow_types field and
# the applet will reject files with extensions that do not match. Please use
# only lower case extensions. The applet will compare for lower, upper and
# mixed case.
#
# example:
#     allow_types=jpg,gif,png,tif,xcf,psd
#
# The reject_message will be shown when the user attempts to upload files that
# should not be allowed. If you enter a text message here it will be displayed
# as a popup. If you enter a url here the page you specify will be loaded in
# the applet.
# example:
#      reject_message=http://localhost/message.php
#
#

allow_types=jpg,gif
reject_message=Solo se permiten archivos de imágenes JPG y GIF


#
# If the user decides not to allow access to the file system, a permission 
# denied message will be shown. Enter a url or a simple text to customize this 
# error message. if you leave it blank, a standard message will be displayed.
#

permission_denied=http://www.pinmo.com.ar/backend/config/uploaderDeniedMsg.php
#permission_denied=http://localhost/devs/pinmo-2.com.ar/backend/config/uploaderDeniedMsg.php

#
# If you are using Rad Upload purely as an Image Upload solution you might want
# to display a thumbnail of each image as it's being uploaded. Please bear in
# mind that the client's computer may not have as much memory as yours, and he
# may find that generating thumbnails for large images to slow down the process.
#
show_thumb=1

#
# If you need to disable the multiple upload feature, and to upload files one
# at a time, switch to bachelor mode. When bachelor property is set the applet
# will complain if you try to upload more than one file. Use the angry_bachelor
# property to set the error message to be displayed.
#
#bachelor=1;
#angry_bachelor=http://www.radupload.com/demo/plus/single.html

#
# Sometimes you might want to bring up a file-open dialog box. If you use the
# browse setting the drop target listens for mouse clicks and brings up a file
# dialog. If instead of clicking on the drop target you wish to display a browse
# button set the browse_button property as well.
# example:
#browse=1
#browse_button=1


#
# The next bit is for image scaling. Images that have either width greater than
# the img_max_width or height greater than img_max_height will be scaled. if you
# set scale_images to yes, you must also asign a valid integer value for
# img_max_width and/or img_max_height.
#
# It should be noted that the java language does not support creating GIF files
# as such all scaled images will be in the JPG format. You will need to set the
# allow_types to match gif,jpg and png if you wish to make use of this feature.
#
# example:
#
#    scale_images=yes
#    img_max_width=100
#    img_max_height=100

scale_images=yes
img_max_width=640
img_max_height=480
jpeg_quality=0.90

#
# Gzip can reduce the size of many file types by 50-70% . For certain file
# types the reduction may be as high as 90% To make use of this feature 
# set the gzip parameter to yes.
#
# Note that this setting has no effect if scale_images has been enabled.
#

# gzip=yes

#
# By default the progress indicator will be hidden (closed) when the upload
# completes. By uncommenting the following line you can continue to keep the
# progress bar visible even after upload has been completed. The user will
# then have to manually close the progress bar.
#
# monitor_keep_visible=yes

#
# If you want to redirect to another page when upload is completed enter it's
# url below. If you specify an external_target the applet will attempt to
# load the url given in external_redir in the target frame. To redirect the
# entire browser window use '_top' as the target.
#
# If you specify a value (in milliseconds 1s = 1000ms) for redirect_delay the
# redirection will take place after the specified period. The default delay is
# 1000ms (1second)
# 
# example:
#external_redir=http://www.youbackitup.com/#nav
#external_target=_top
# redirect_delay=1000

#
# Javascript notification
# If you switch on jsnotify property the applet will call a javascript function
# with the following signature when the upload has been completed.
#
#    uploadCompleted(s)
#

jsnotify=yes