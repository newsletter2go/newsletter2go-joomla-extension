DROP TABLE IF EXISTS #__newsletter2go;
 
CREATE TABLE #__newsletter2go (
    id INT(11) NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(25) NOT NULL,
    `value` text,
    PRIMARY KEY  (id)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
 
INSERT INTO #__newsletter2go (`name`, `value`) VALUES
        ('apiKey', ''),
        ('doiCode', ''),
        ('fields', NULL),
        ('texts', '{"failureSubsc":"Thank you for signing up. You are already signed up and will continue to receive our newsletter.","success":"Thank you for signing up. We have sent you an email with a confirmation link. Please check your inbox.","failureEmail":"The email address you inserted does not seem to be valid. Please correct it.","failureError":"We were not able to sign you up. Please try again.","failureRequired":"Please fill all fields.","buttonText":"Subscribe now!"}' ),
        ('widget', NULL),
        ('colors', NULL);