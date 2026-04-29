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

	const growthModal = document.querySelector( '[data-wps-etmfw-growth-modal="true"]' );
	if ( growthModal && window.etmfw_admin_ui ) {
		const openTriggers = document.querySelectorAll( '[data-wps-etmfw-growth-open="true"]' );
		const closeTriggers = growthModal.querySelectorAll( '[data-wps-etmfw-growth-close="true"]' );
		const growthForm = growthModal.querySelector( '[data-wps-etmfw-growth-form="true"]' );
		const formPanel = growthModal.querySelector( '[data-wps-etmfw-growth-form-panel="true"]' );
		const thankYouPanel = growthModal.querySelector( '[data-wps-etmfw-growth-thankyou-panel="true"]' );
		const statusArea = growthModal.querySelector( '[data-wps-etmfw-growth-status="true"]' );
		const submitButton = growthModal.querySelector( '[data-wps-etmfw-growth-submit="true"]' );
		const thankYouMessage = growthModal.querySelector( '[data-wps-etmfw-growth-thankyou-message="true"]' );
		const defaultThankYouMessage = thankYouMessage ? thankYouMessage.textContent.trim() : '';
		const body = document.body;
		let redirectTimer = null;
		let lastFocusedTrigger = null;
		const clearAutoOpenFlag = () => {
			if ( typeof URL === 'undefined' ) {
				return;
			}

			const url = new URL( window.location.href );
			if ( url.searchParams.get( 'etmfw_open' ) !== 'talk-to-expert' ) {
				return;
			}

			url.searchParams.delete( 'etmfw_open' );
			window.history.replaceState( window.history.state, '', url );
		};

		const shouldAutoOpenModal = () => {
			if ( typeof URL === 'undefined' ) {
				return false;
			}

			return new URL( window.location.href ).searchParams.get( 'etmfw_open' ) === 'talk-to-expert';
		};

		const clearRedirectTimer = () => {
			if ( redirectTimer ) {
				window.clearTimeout( redirectTimer );
				redirectTimer = null;
			}
		};

		const showStatus = ( message ) => {
			if ( ! statusArea ) {
				return;
			}

			statusArea.textContent = message;
			statusArea.hidden = ! message;
		};

		const hideStatus = () => {
			if ( ! statusArea ) {
				return;
			}

			statusArea.textContent = '';
			statusArea.hidden = true;
		};

		const setButtonState = ( isLoading ) => {
			if ( ! submitButton ) {
				return;
			}

			submitButton.disabled = isLoading;
			submitButton.textContent = isLoading ? submitButton.dataset.loadingLabel : submitButton.dataset.defaultLabel;
		};

		const restoreDefaultView = () => {
			clearRedirectTimer();
			hideStatus();
			if ( growthForm ) {
				growthForm.reset();
			}
			if ( formPanel ) {
				formPanel.hidden = false;
			}
			if ( thankYouPanel ) {
				thankYouPanel.hidden = true;
			}
			if ( thankYouMessage ) {
				thankYouMessage.textContent = defaultThankYouMessage || etmfw_admin_ui.strings.defaultSuccess;
			}
			growthModal.classList.remove( 'is-submitted' );
			setButtonState( false );
		};

		const openModal = ( trigger ) => {
			lastFocusedTrigger = trigger || document.activeElement;
			restoreDefaultView();
			growthModal.hidden = false;
			growthModal.setAttribute( 'aria-hidden', 'false' );
			body.classList.add( 'wps-etmfw-growth-modal-open' );
			window.requestAnimationFrame( () => {
				growthModal.classList.add( 'is-open' );
				const firstInput = growthModal.querySelector( 'input, select, textarea, button' );
				if ( firstInput ) {
					firstInput.focus();
				}
			} );
		};

		const closeModal = () => {
			clearRedirectTimer();
			growthModal.classList.remove( 'is-open' );
			growthModal.setAttribute( 'aria-hidden', 'true' );
			body.classList.remove( 'wps-etmfw-growth-modal-open' );
			window.setTimeout( () => {
				if ( growthModal.getAttribute( 'aria-hidden' ) === 'true' ) {
					growthModal.hidden = true;
				}
			}, 180 );
			restoreDefaultView();
			if ( lastFocusedTrigger && typeof lastFocusedTrigger.focus === 'function' ) {
				lastFocusedTrigger.focus();
			}
		};

		const serializeForm = ( form ) => {
			const payload = {};
			const formData = new window.FormData( form );

			formData.forEach( ( value, key ) => {
				const normalizedKey = key.endsWith( '[]' ) ? key.slice( 0, -2 ) : key;
				if ( Object.prototype.hasOwnProperty.call( payload, normalizedKey ) ) {
					if ( ! Array.isArray( payload[ normalizedKey ] ) ) {
						payload[ normalizedKey ] = [ payload[ normalizedKey ] ];
					}
					payload[ normalizedKey ].push( value );
				} else {
					payload[ normalizedKey ] = value;
				}
			} );

			return payload;
		};

		openTriggers.forEach( ( trigger ) => {
			trigger.addEventListener( 'click', () => openModal( trigger ) );
		} );

		closeTriggers.forEach( ( trigger ) => {
			trigger.addEventListener( 'click', closeModal );
		} );

		growthModal.addEventListener( 'click', ( event ) => {
			if ( event.target === growthModal ) {
				closeModal();
			}
		} );

		document.addEventListener( 'keydown', ( event ) => {
			if ( event.key === 'Escape' && growthModal.getAttribute( 'aria-hidden' ) === 'false' ) {
				closeModal();
			}
		} );

		if ( shouldAutoOpenModal() ) {
			clearAutoOpenFlag();
			openModal( null );
		}

		if ( growthForm ) {
			growthForm.addEventListener( 'submit', async ( event ) => {
				event.preventDefault();
				hideStatus();
				setButtonState( true );

				const payload = new window.URLSearchParams();
				payload.set( 'action', etmfw_admin_ui.action );
				payload.set( 'nonce', etmfw_admin_ui.nonce );
				payload.set( 'form_data', JSON.stringify( serializeForm( growthForm ) ) );

				try {
					const response = await window.fetch( etmfw_admin_ui.ajaxurl, {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
						},
						body: payload.toString(),
						credentials: 'same-origin',
					} );

					const result = await response.json();
					if ( ! response.ok || ! result.success ) {
						showStatus( result && result.data && result.data.message ? result.data.message : etmfw_admin_ui.strings.genericError );
						setButtonState( false );
						return;
					}

					growthForm.reset();
					if ( formPanel ) {
						formPanel.hidden = true;
					}
					if ( thankYouPanel ) {
						thankYouPanel.hidden = false;
					}
					if ( thankYouMessage ) {
						thankYouMessage.textContent = result.data && result.data.message ? result.data.message : ( defaultThankYouMessage || etmfw_admin_ui.strings.defaultSuccess );
					}
					growthModal.classList.add( 'is-submitted' );
					setButtonState( false );
					clearRedirectTimer();
					redirectTimer = window.setTimeout( () => {
						window.location.assign( window.location.href );
					}, 4000 );
				} catch ( error ) {
					showStatus( etmfw_admin_ui.strings.genericError );
					setButtonState( false );
				}
			} );
		}
	}
});
