<aside
    id="interface-config"
    class="config-panel"
    aria-labelledby="config-title"
    aria-hidden="true"
    hidden
    inert
>
    <div class="config-panel__header">
        <div>
            <p class="system-label">Interface config</p>
            <h2 id="config-title">Runtime preferences</h2>
        </div>
        <button
            class="icon-button"
            type="button"
            data-config-close
            aria-label="Tutup konfigurasi"
        >
            <svg aria-hidden="true" viewBox="0 0 24 24">
                <path d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>
    </div>

    <div class="config-panel__body">
        <fieldset class="config-group">
            <legend><span>01</span> Visual profile</legend>
            <div class="segmented-control" data-preference-group="theme">
                <button type="button" data-value="engineering">Engineering</button>
                <button type="button" data-value="minimal">Minimal</button>
                <button type="button" data-value="terminal">Terminal</button>
            </div>
        </fieldset>

        <fieldset class="config-group">
            <legend><span>02</span> Render profile</legend>
            <div class="segmented-control" data-preference-group="render">
                <button type="button" data-value="high">High</button>
                <button type="button" data-value="balanced">Balanced</button>
                <button type="button" data-value="saver">Saver</button>
            </div>
            <p>Controls decorative rendering only. Content remains unchanged.</p>
        </fieldset>

        <fieldset class="config-group">
            <legend><span>03</span> Motion</legend>
            <div class="segmented-control" data-preference-group="motion">
                <button type="button" data-value="system">System</button>
                <button type="button" data-value="reduced">Reduced</button>
            </div>
        </fieldset>

        <fieldset class="config-group">
            <legend><span>04</span> Sound layer</legend>
            <div class="segmented-control" data-preference-group="sound">
                <button type="button" data-value="off">Off</button>
                <button type="button" data-value="fx">Interface FX</button>
            </div>
            <p>Sound stays off until you explicitly enable it.</p>
        </fieldset>
    </div>

    <div class="config-panel__footer">
        <span>Preferences remain on this device.</span>
        <button class="text-button" type="button" data-preferences-reset>
            Reset defaults
        </button>
    </div>
</aside>
<button
    class="config-backdrop"
    type="button"
    data-config-close
    aria-label="Tutup konfigurasi"
    tabindex="-1"
></button>
