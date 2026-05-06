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

	// Tab switching with page refresh to ensure proper CSS loading
	const tabLinks = document.querySelectorAll('.wps-etmfw-ui-tab[data-tab-key]');

	// Remove the default click prevention and let tabs work as regular links
	// This will cause page refresh which ensures CSS loads properly
	tabLinks.forEach( ( link ) => {
		// Remove any existing click handlers that prevent default
		link.removeEventListener( 'click', function(e) { e.preventDefault(); } );

		// Ensure the link works normally for page refresh
		link.addEventListener( 'click', ( ) => {
			// Allow the default link behavior to trigger page refresh
			// The href already contains the proper URL with etmfw_tab parameter
			// No need to prevent default or manipulate history
		} );
	} );

	// Initialize color pickers if present on the current tab
	const tabsContainer = document.querySelector('.wps-etmfw-tab-panels');
	if ( tabsContainer && typeof window.etmfwInitColorPickers === 'function' ) {
		const activePanel = tabsContainer.querySelector( '.wps-etmfw-tab-panel.is-active' );
		if ( activePanel ) {
			window.requestAnimationFrame( () => {
				window.etmfwInitColorPickers( activePanel );
			} );
		}
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



