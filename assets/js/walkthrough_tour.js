(function () {
  function currentSection() {
    var path = window.location.pathname;
    if (path.indexOf('/super_admin/') !== -1) return 'super_admin';
    if (path.indexOf('/user/') !== -1) return 'user';
    return 'admin';
  }

  var DASHBOARD_STEPS = [
    {
      file: 'index.php',
      selector: '.text_header',
      title: 'Dashboard',
      text: 'Your landing page. It gives you an at-a-glance overview of rooms and today\'s bookings, plus the full booking calendar.'
    },
    {
      file: 'index.php',
      selector: '#wt-rooms-box',
      title: 'Total Rooms',
      text: 'Shows the total number of rooms registered in the system. Click it to view the full room list.'
    },
    {
      file: 'index.php',
      selector: '#wt-availability-box',
      title: 'Room Availability',
      text: 'These cards show how many rooms are available, partially occupied, or fully occupied today. Click a card to jump to that filtered list.'
    },
    {
      file: 'index.php',
      selector: '#wt-bookings-box',
      title: 'Today\'s Bookings',
      text: 'These cards summarize ongoing, upcoming, done, and cancelled reservations for today. Click a card to see the matching reservations.'
    },
    {
      file: 'index.php',
      selector: '[data-wt="calendar"]',
      title: 'Booking Calendar',
      text: 'Every reservation is plotted here. Switch between month, week, and day views, and click an event to see its full details.'
    }
  ];

  var FLOORS_ROOMS_HISTORY_RESERVATIONS_STEPS = [
    {
      file: 'floors.php',
      selector: '.text_header',
      title: 'Floors',
      text: 'Manage the building floors that rooms belong to. Add a new floor, search existing ones, or edit/delete a floor.'
    },
    {
      file: 'rooms.php',
      selector: '.text_header',
      title: 'Rooms',
      text: 'Manage meeting rooms. Filter by availability, and click a room to view or edit its details.'
    },
    {
      file: 'rooms.php',
      selector: '.btn_add',
      title: 'Add a Room',
      text: 'Click here to register a new room. Let\'s take a look at the form.'
    },
    {
      file: 'room_add.php',
      selector: '.modal-content',
      insideModal: true,
      title: 'Add Room Form',
      text: 'Enter the room name, serial number, floor, capacity, an image, and a description, then save to add the room.'
    },
    {
      file: 'history.php',
      selector: '.text_header',
      title: 'History',
      text: 'Browse past and completed bookings for reporting or auditing. Search by room, requester, or date.'
    },
    {
      file: 'reservations.php',
      selector: '.text_header',
      title: 'Reservations',
      text: 'View and manage all reservations. Filter by ongoing, upcoming, done, or cancelled.'
    },
    {
      file: 'reservation_add.php',
      selector: '.modal-content',
      insideModal: true,
      title: 'Add Reservation Form',
      text: 'Pick a date and time range and fill in the details to reserve a room on behalf of a requester, then save.'
    }
  ];

  var ADMIN_STEPS = DASHBOARD_STEPS.concat(FLOORS_ROOMS_HISTORY_RESERVATIONS_STEPS);

  var SUPER_ADMIN_STEPS = DASHBOARD_STEPS.concat([
    {
      file: 'accounts.php',
      selector: '.text_header',
      title: 'Accounts',
      text: 'Manage admin accounts for the system. Search for an employee and assign them as an admin.'
    },
    {
      file: 'accounts.php',
      selector: '.btn_add',
      title: 'Add an Account',
      text: 'Click here to assign an employee as an admin. Let\'s take a look at the form.'
    },
    {
      file: 'account_add.php',
      selector: '.modal-content',
      insideModal: true,
      title: 'Assign Account Form',
      text: 'Search for an employee by name and assign them as an admin account.'
    }
  ], FLOORS_ROOMS_HISTORY_RESERVATIONS_STEPS);

  var USER_STEPS = [
    {
      file: 'index.php',
      selector: '.text_header',
      title: 'Rooms',
      text: 'Your landing page. Browse all meeting rooms and see which ones are open.'
    },
    {
      file: 'index.php',
      selector: '.filter_options',
      title: 'Filter Rooms',
      text: 'Filter rooms by Available, Partially Occupied, or Fully Occupied status. Click a room afterward to view its details.'
    },
    {
      file: 'reservations.php',
      selector: '.text_header',
      title: 'Reservations',
      text: 'View and manage your own reservations. Filter by ongoing, upcoming, done, or cancelled, and search by floor or date.'
    },
    {
      file: 'reservation_add.php',
      selector: '.modal-content',
      insideModal: true,
      title: 'Book a Room',
      text: 'Pick a date and time range and fill in the meeting details, then save to submit your reservation.'
    }
  ];

  var SECTION_STEPS = {
    admin: ADMIN_STEPS,
    super_admin: SUPER_ADMIN_STEPS,
    user: USER_STEPS
  };

  var TOUR_STEPS = SECTION_STEPS[currentSection()];
  var STEP_KEY = 'wt_tour_step_' + currentSection();
  var SEEN_KEY = 'wt_tour_seen_' + currentSection();
  var GUIDE_FILE = 'walkthrough.php';

  var overlay = null;
  var tooltip = null;

  function currentFile() {
    var path = window.location.pathname;
    var file = path.substring(path.lastIndexOf('/') + 1);
    return file || 'index.php';
  }

  function getStepIndex() {
    var raw = sessionStorage.getItem(STEP_KEY);
    return raw === null ? null : parseInt(raw, 10);
  }

  function setStepIndex(i) {
    sessionStorage.setItem(STEP_KEY, String(i));
  }

  function endTour(redirectToGuide) {
    sessionStorage.removeItem(STEP_KEY);
    try { localStorage.setItem(SEEN_KEY, '1'); } catch (e) {}
    removeUI();
    if (redirectToGuide && currentFile() !== GUIDE_FILE) {
      window.location.href = GUIDE_FILE;
    }
  }

  function removeUI() {
    document.querySelectorAll('.wt-highlight').forEach(function (el) {
      el.classList.remove('wt-highlight');
    });
    if (overlay) { overlay.remove(); overlay = null; }
    if (tooltip) { tooltip.remove(); tooltip = null; }
  }

  function buildUI() {
    overlay = document.createElement('div');
    overlay.className = 'wt-overlay';

    tooltip = document.createElement('div');
    tooltip.className = 'wt-tooltip';
    tooltip.innerHTML =
      '<div class="wt-tooltip-title"></div>' +
      '<div class="wt-tooltip-text"></div>' +
      '<div class="wt-tooltip-footer">' +
      '<span class="wt-step-count"></span>' +
      '<div class="wt-tooltip-actions">' +
      '<button type="button" class="wt-btn wt-skip">Skip</button>' +
      '<button type="button" class="wt-btn wt-prev">Back</button>' +
      '<button type="button" class="wt-btn wt-primary wt-next">Next</button>' +
      '</div></div>';

    document.body.appendChild(overlay);
    document.body.appendChild(tooltip);

    tooltip.querySelector('.wt-skip').addEventListener('click', function () { endTour(false); });
    tooltip.querySelector('.wt-next').addEventListener('click', function () {
      var i = getStepIndex();
      goToStep(i === null ? 0 : i + 1);
    });
    tooltip.querySelector('.wt-prev').addEventListener('click', function () {
      var i = getStepIndex();
      if (i !== null && i > 0) goToStep(i - 1);
    });

    window.addEventListener('resize', function () {
      if (tooltip && tooltip.dataset.targetSelector) {
        var target = document.querySelector(tooltip.dataset.targetSelector);
        if (target) positionTooltip(target);
      }
    });
  }

  function centerTooltip() {
    tooltip.style.position = 'fixed';
    tooltip.style.top = '50%';
    tooltip.style.left = '50%';
    tooltip.style.bottom = 'auto';
    tooltip.style.transform = 'translate(-50%, -50%)';
  }

  function positionTooltip(target) {
    var isMobile = window.innerWidth < 576;
    var rect = target.getBoundingClientRect();

    if (isMobile) {
      tooltip.style.position = 'fixed';
      tooltip.style.transform = 'none';
      tooltip.style.left = '4vw';
      tooltip.style.width = '92vw';
      tooltip.style.top = 'auto';
      tooltip.style.bottom = '16px';
      return;
    }

    var tw = tooltip.offsetWidth || 300;
    var top = rect.bottom + window.scrollY + 12;
    var left = rect.left + window.scrollX;

    if (left + tw + 10 > window.innerWidth + window.scrollX) {
      left = window.innerWidth + window.scrollX - tw - 10;
    }
    if (left < 10) left = 10;

    if (top + 160 > window.innerHeight + window.scrollY) {
      top = rect.top + window.scrollY - 170;
      if (top < window.scrollY + 10) top = window.scrollY + 10;
    }

    tooltip.style.position = 'absolute';
    tooltip.style.transform = 'none';
    tooltip.style.width = '';
    tooltip.style.bottom = 'auto';
    tooltip.style.top = top + 'px';
    tooltip.style.left = left + 'px';
  }

  function render(i) {
    if (!overlay || !tooltip) buildUI();

    document.querySelectorAll('.wt-highlight').forEach(function (el) {
      el.classList.remove('wt-highlight');
    });

    var step = TOUR_STEPS[i];
    var target = step.selector ? document.querySelector(step.selector) : null;

    overlay.classList.toggle('wt-overlay-hidden', !!step.insideModal);

    tooltip.querySelector('.wt-tooltip-title').textContent = step.title || '';
    tooltip.querySelector('.wt-tooltip-text').textContent = step.text || '';
    tooltip.querySelector('.wt-step-count').textContent = (i + 1) + ' of ' + TOUR_STEPS.length;
    tooltip.querySelector('.wt-prev').style.visibility = i === 0 ? 'hidden' : 'visible';
    tooltip.querySelector('.wt-next').textContent = i === TOUR_STEPS.length - 1 ? 'Finish' : 'Next';

    if (target) {
      target.classList.add('wt-highlight');
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
      tooltip.dataset.targetSelector = step.selector;
      setTimeout(function () { positionTooltip(target); }, 350);
    } else {
      delete tooltip.dataset.targetSelector;
      centerTooltip();
    }
  }

  function goToStep(i) {
    if (i < 0) return;
    if (i >= TOUR_STEPS.length) { endTour(true); return; }

    setStepIndex(i);
    var step = TOUR_STEPS[i];

    if (step.file === currentFile()) {
      render(i);
    } else {
      window.location.href = step.file;
    }
  }

  function init() {
    var urlParams = new URLSearchParams(window.location.search);
    var forceStart = urlParams.get('tour') === '1';
    var idx = getStepIndex();

    if (idx === null) {
      if (forceStart) {
        idx = 0;
      } else {
        var seen = false;
        try { seen = localStorage.getItem(SEEN_KEY) === '1'; } catch (e) {}
        if (!seen && currentFile() === 'index.php') {
          idx = 0;
        } else {
          return;
        }
      }
      setStepIndex(idx);
    }

    var step = TOUR_STEPS[idx];
    if (!step || step.file !== currentFile()) return;

    render(idx);
  }

  document.addEventListener('DOMContentLoaded', init);

  window.AdminWalkthrough = {
    start: function () { goToStep(0); }
  };
})();
