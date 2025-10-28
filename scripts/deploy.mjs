#!/usr/bin/env node

import gcloud from '@battis/partly-gcloudy';
import { Core } from '@qui-cli/core';
import { Shell } from '@qui-cli/shell';

(async () => {
  const {
    values: { force }
  } = await Core.init({ flag: { force: { short: 'f' } } });
  const configure = force || !process.env.PROJECT;
  const { project } = await gcloud.batch.appEngineDeployAndCleanup({
    retainVersions: 2
  });
  if (configure) {
    Shell.exec(
      `gcloud app update --ssl-policy=TLS_VERSION_1_2 --project="${project.projectId}" --format=json`
    );
  }
})();
