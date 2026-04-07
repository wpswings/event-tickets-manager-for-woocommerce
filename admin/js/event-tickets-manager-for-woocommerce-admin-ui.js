document.addEventListener('DOMContentLoaded', () => {
	const toggleInputs = document.querySelectorAll('.wps-etmfw-ui-toggle__input[role="switch"]');

	toggleInputs.forEach((input) => {
		const syncState = () => {
			input.setAttribute('aria-checked', input.checked ? 'true' : 'false');
			const toggle = input.closest('.wps-etmfw-ui-toggle');
			if (toggle) {
				toggle.setAttribute('data-state', input.checked ? 'on' : 'off');
			}
		};

		syncState();
		input.addEventListener('change', syncState);
	});

	const tabsContainer = document.querySelector('.wps-etmfw-tab-panels');
	const tabLinks = document.querySelectorAll('.wps-etmfw-ui-tab[data-tab-key]');
	if ( tabsContainer && tabLinks.length && typeof URL !== 'undefined' ) {
		let activeTab = tabsContainer.dataset.activeTab || tabLinks[0].dataset.tabKey;

		const setActiveTab = ( tabKey, updateHistory = false ) => {
			if ( ! tabKey || activeTab === tabKey ) {
				return;
			}

			const targetPanel = tabsContainer.querySelector( `.wps-etmfw-tab-panel[data-tab-key="${ tabKey }"]` );
			if ( ! targetPanel ) {
				return;
			}

			tabLinks.forEach( ( link ) => {
				link.classList.toggle( 'is-active', link.dataset.tabKey === tabKey );
			} );

			tabsContainer.querySelectorAll( '.wps-etmfw-tab-panel' ).forEach( ( panel ) => {
				const isActive = panel.dataset.tabKey === tabKey;
				panel.classList.toggle( 'is-active', isActive );
				if ( isActive ) {
					panel.removeAttribute( 'hidden' );
					panel.setAttribute( 'aria-hidden', 'false' );
				} else {
					panel.setAttribute( 'hidden', '' );
					panel.setAttribute( 'aria-hidden', 'true' );
				}
			} );

			activeTab = tabKey;
			tabsContainer.dataset.activeTab = tabKey;
			if ( updateHistory ) {
				const url = new URL( window.location.href );
				url.searchParams.set( 'etmfw_tab', tabKey );
				window.history.pushState( { etmfwTab: tabKey }, '', url );
			}
		};

		const getTabFromHistory = () => {
			const params = new URL( window.location.href ).searchParams;
			return params.get( 'etmfw_tab' );
		};

		tabLinks.forEach( ( link ) => {
			link.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				const tabKey = link.dataset.tabKey;
				if ( tabKey ) {
					setActiveTab( tabKey, true );
				}
			} );
		} );

		window.addEventListener( 'popstate', ( event ) => {
			const stateTab = event.state && event.state.etmfwTab ? event.state.etmfwTab : getTabFromHistory();
			if ( stateTab ) {
				setActiveTab( stateTab, false );
			}
		} );

		setActiveTab( activeTab, false );
	}
});
