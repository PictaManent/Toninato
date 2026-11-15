<?php
/**
 * Template Name: Home
 *
 * Homepage. Il testo di presentazione è il contenuto della pagina (modificabile dall'editor).
 */

get_header();

$info = toninato_info();
?>

<main id="main" class="site-main">
	<?php while ( have_posts() ) : the_post(); ?>

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
					<a class="btn btn--ghost" href="<?php echo esc_url( toninato_page_url( 'contatti' ) ); ?>">Contatti</a>
				</div>
			</div>
		</div>
	</section>

	<!-- Presentazione (contenuto della pagina) -->
	<section class="intro">
		<div class="container">
			<div class="intro__card">
				<div class="intro__text">
					<?php the_content(); ?>
					<a class="link-more" href="<?php echo esc_url( toninato_page_url( 'lo-studio' ) ); ?>">
						Scopri lo studio <?php echo toninato_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					</a>
				</div>
				<figure class="intro__media">
					<?php if ( has_post_thumbnail() ) : ?>
						<?php the_post_thumbnail( 'large', array( 'loading' => 'eager' ) ); ?>
					<?php else : ?>
						<img src="<?php echo esc_url( toninato_img( 'avvocato-toninato.jpg' ) ); ?>" alt="L'Avvocato Stefania Toninato nel suo studio di Moncalieri" width="1350" height="550" loading="eager">
					<?php endif; ?>
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
			<?php get_template_part( 'template-parts/services-grid' ); ?>
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
					<a class="link-more" href="<?php echo esc_url( toninato_page_url( 'diritto-civile' ) ); ?>">
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
					<a class="link-more" href="<?php echo esc_url( toninato_page_url( 'diritto-penale' ) ); ?>">
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
					'studio-ufficio.jpg'       => 'Ufficio dell\'Avvocato Toninato',
					'studio-ingresso.jpg'      => 'Sala d\'attesa dello studio',
					'studio-scrivania.jpg'     => 'Scrivania dello studio legale',
					'studio-sala-riunioni.jpg' => 'Sala riunioni dello studio',
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

	<?php get_template_part( 'template-parts/cta' ); ?>

	<?php endwhile; ?>
</main>

<?php
get_footer();
