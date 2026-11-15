<?php
/**
 * Homepage.
 */

get_header();

$info = toninato_info();
?>

<main id="main" class="site-main">

	<!-- Hero -->
	<section class="hero" style="--hero-image: url('<?php echo esc_url( toninato_img( 'studio-ufficio.jpg' ) ); ?>');">
		<div class="container hero__inner">
			<div class="hero__content">
				<span class="hero__mark" aria-hidden="true">ST</span>
				<p class="hero__title"><?php echo esc_html( $info['title'] ); ?></p>
				<p class="hero__name"><?php echo esc_html( $info['name'] ); ?></p>
				<p class="hero__line"><?php echo esc_html( $info['qualifica'] ); ?></p>
				<p class="hero__line">Consulenza e assistenza in diritto civile e penale</p>
				<p class="hero__place"><?php echo esc_html( $info['city'] . ' (' . $info['province'] . ')' ); ?></p>
				<div class="hero__actions">
					<a class="btn btn--primary" href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>">Chiama lo studio</a>
					<a class="btn btn--ghost" href="<?php echo esc_url( home_url( '/contatti/' ) ); ?>">Contatti</a>
				</div>
			</div>
		</div>
	</section>

	<!-- Presentazione -->
	<section class="intro">
		<div class="container">
			<div class="intro__card">
				<div class="intro__text">
					<h1 class="intro__heading">Assistenza e consulenza legale in diritto civile e penale a Moncalieri</h1>
					<p>
						L'Avvocato <strong>Stefania Toninato</strong> offre un servizio di consulenza legale
						presso lo studio in <strong>via Cavour 38, a Moncalieri</strong>, in provincia di Torino.
						Grazie a una consolidata esperienza nel settore e a un percorso di formazione in costante
						aggiornamento, garantisce assistenza legale all'insegna della <strong>serietà</strong>,
						della <strong>discrezione</strong> e della <strong>professionalità</strong>,
						dal primo all'ultimo grado di giudizio.
					</p>

					<h2 class="intro__heading">Garanzia di professionalità</h2>
					<p>
						Laureata a pieni voti presso l'<strong>Università degli Studi di Torino</strong>, con studio
						a Moncalieri dal <?php echo esc_html( $info['since'] ); ?>, l'Avvocato Toninato assiste
						<strong>privati e aziende</strong> in controversie civili e penali, <strong>fino al giudizio in Cassazione</strong>.
						Avvalendosi della collaborazione di medici legali, ingegneri, architetti, commercialisti
						e consulenti del lavoro, lo studio offre un servizio di consulenza e assistenza legale completo.
					</p>
					<a class="link-more" href="<?php echo esc_url( home_url( '/lo-studio/' ) ); ?>">
						Scopri lo studio <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
				<figure class="intro__media">
					<img src="<?php echo esc_url( toninato_img( 'avvocato-toninato.jpg' ) ); ?>" alt="L'Avvocato Stefania Toninato nel suo studio di Moncalieri" width="1350" height="550" loading="eager">
				</figure>
			</div>
		</div>
	</section>

	<!-- Aree di attività -->
	<section class="section services" id="aree-di-attivita">
		<div class="container">
			<header class="section-head">
				<p class="overline">Di cosa si occupa lo studio</p>
				<h2 class="section-title">Aree di attività</h2>
				<p class="section-lead">Assistenza in controversie civili e penali, fino al giudizio in Cassazione.</p>
			</header>

			<div class="services__grid">
				<?php foreach ( toninato_services() as $service ) : ?>
					<article class="service-card">
						<a class="service-card__link" href="<?php echo esc_url( home_url( '/' . $service['slug'] . '/' ) ); ?>">
							<div class="service-card__media">
								<img src="<?php echo esc_url( toninato_img( $service['image'] ) ); ?>" alt="" width="900" height="600" loading="lazy">
							</div>
							<div class="service-card__body">
								<h3 class="service-card__title"><?php echo esc_html( $service['title'] ); ?></h3>
								<p class="service-card__text"><?php echo esc_html( $service['text'] ); ?></p>
								<span class="service-card__more">Scopri di più <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Valori -->
	<section class="section values">
		<div class="container">
			<header class="section-head section-head--light">
				<p class="overline">Perché scegliere lo studio</p>
				<h2 class="section-title">Valori e punti di forza</h2>
			</header>

			<div class="values__grid">
				<div class="value">
					<span class="value__icon"><?php echo toninato_icon( 'scale' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="value__title">Fino in Cassazione</h3>
					<p>Avvocato patrocinante in Cassazione: assistenza dal primo all'ultimo grado di giudizio.</p>
				</div>
				<div class="value">
					<span class="value__icon"><?php echo toninato_icon( 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="value__title">Discrezione ed etica</h3>
					<p>Ogni incarico è seguito con grande discrezione, etica e nel pieno rispetto del codice deontologico.</p>
				</div>
				<div class="value">
					<span class="value__icon"><?php echo toninato_icon( 'chat' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="value__title">Contatto diretto</h3>
					<p>Rapporto diretto con il cliente e reperibilità telefonica immediata: risposta subito o entro un'ora.</p>
				</div>
				<div class="value">
					<span class="value__icon"><?php echo toninato_icon( 'users' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
					<h3 class="value__title">Rete di professionisti</h3>
					<p>Collaborazione con medici legali, ingegneri, architetti, commercialisti e consulenti del lavoro.</p>
				</div>
			</div>
		</div>
	</section>

	<!-- Ambiti -->
	<section class="section areas">
		<div class="container areas__grid">
			<article class="area">
				<div class="area__media">
					<img src="<?php echo esc_url( toninato_img( 'targa-studio.jpg' ) ); ?>" alt="Targa dello studio dell'Avvocato Stefania Toninato" width="1600" height="898" loading="lazy">
				</div>
				<div class="area__body">
					<p class="overline">Privati e aziende</p>
					<h2 class="area__title">Diritto civile</h2>
					<p>
						Assistenza legale in ambito civile a persone fisiche e aziende: famiglia, risarcimento danni,
						successioni, locazioni, condominio, recupero crediti, contratti, procedure fallimentari,
						aste e ricerca immobili, ristrutturazione del debito.
					</p>
					<a class="link-more" href="<?php echo esc_url( home_url( '/diritto-civile/' ) ); ?>">
						Approfondisci <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
			</article>
			<article class="area">
				<div class="area__media">
					<img src="<?php echo esc_url( toninato_img( 'biglietto-studio.jpg' ) ); ?>" alt="Biglietto da visita dello Studio Legale Toninato" width="1920" height="1280" loading="lazy">
				</div>
				<div class="area__body">
					<p class="overline">Tutela della persona</p>
					<h2 class="area__title">Diritto penale</h2>
					<p>
						Assistenza completa per la redazione di querele e nei processi con costituzione
						di parte civile, fino all'ultimo grado di giudizio.
					</p>
					<a class="link-more" href="<?php echo esc_url( home_url( '/diritto-penale/' ) ); ?>">
						Approfondisci <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
			</article>
		</div>
	</section>

	<!-- Galleria -->
	<section class="section gallery">
		<div class="container">
			<header class="section-head">
				<p class="overline">Via Cavour 38, Moncalieri</p>
				<h2 class="section-title">Nello studio</h2>
			</header>
			<div class="gallery__grid">
				<?php
				$photos = array(
					'studio-ufficio.jpg'        => 'Ufficio dell\'Avvocato Toninato',
					'studio-ingresso.jpg'       => 'Sala d\'attesa dello studio',
					'studio-scrivania.jpg'      => 'Scrivania dello studio legale',
					'studio-sala-riunioni.jpg'  => 'Sala riunioni dello studio',
				);
				foreach ( $photos as $file => $alt ) :
					?>
					<figure class="gallery__item">
						<img src="<?php echo esc_url( toninato_img( $file ) ); ?>" alt="<?php echo esc_attr( $alt ); ?>" width="1920" height="1280" loading="lazy">
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- Contatti -->
	<section class="section cta">
		<div class="container">
			<div class="cta__card">
				<h2 class="cta__title">Hai bisogno di un avvocato a Moncalieri?</h2>
				<p class="cta__subtitle">Contatta lo studio:</p>
				<div class="cta__phones">
					<a href="tel:<?php echo esc_attr( $info['phone']['tel'] ); ?>"><?php echo toninato_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $info['phone']['label'] ); ?></a>
					<a href="tel:<?php echo esc_attr( $info['mobile']['tel'] ); ?>"><?php echo toninato_icon( 'mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $info['mobile']['label'] ); ?></a>
				</div>
				<a class="cta__email" href="mailto:<?php echo esc_attr( antispambot( $info['email'] ) ); ?>">
					<?php echo toninato_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( antispambot( $info['email'] ) ); ?>
				</a>
				<p class="cta__note">
					<?php echo toninato_icon( 'clock' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					Lo studio riceve solo su appuntamento. Si garantisce risposta immediata o un nuovo contatto entro un'ora.
				</p>
			</div>
		</div>
	</section>

</main>

<?php
get_footer();
