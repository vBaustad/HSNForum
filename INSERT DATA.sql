INSERT INTO forum.kategori(kat_navn) 
VALUES 	('Informatikk'),
		('Økonomi og administrasjon'),
		('Turisme og ledelse');

INSERT INTO forum.underkategori(kat_id, ukat_navn, ukat_beskrivelse, ukat_img, ukat_img_farge) 
VALUES 	('1', 'java', 'ALt om Java', 'fa fa-user-plus fa-2x ', 'cyan'),
		('1', 'Databaser og Web', 'ALt om Databaser og Web', 'fa fa-user-plus fa-2x ', 'cyan'),
        ('2', 'Matte', 'ALt om matte', 'fa fa-user-plus fa-2x ', 'cyan'),
        ('3', 'Informasjonsbehandling', 'ALt om Informasjonsbehandling', 'fa fa-user-plus fa-2x ', 'cyan');
        
