#!/usr/bin/env node

import gcloud from '@battis/partly-gcloudy';
import select from '@inquirer/select';
import { Core } from '@qui-cli/core';
import { Env } from '@qui-cli/env-1password';
import { Shell } from '@qui-cli/shell';
import fs from 'node:fs';

(async () => {
  const {
    values: { force }
  } = await Core.init({ flag: { force: { short: 'f' } } });
  const configure = force || !process.env.PROJECT;
  let projectId = await Env.get({ key: 'PROJECT' });
  let region = await Env.get({ key: 'REGION' });
  if (configure) {
    const project = await gcloud.projects.create({
      projectId,
      reuseIfExists: true
    });
    projectId = project.projectId;
    await Env.set({ key: 'PROJECT', value: projectId });
    region = await select({
      message: 'Which region would you like to deploy to?',
      default: region,
      choices: JSON.parse(
        Shell.exec(
          `gcloud run regions list --project=${projectId} --format=json`
        ).stdout
      ).map((r) => ({
        name: `${r.locationId} (${r.displayName})`,
        value: r.locationId
      }))
    });
    await gcloud.services.enable(gcloud.services.API.CloudRunAdminAPI);
    await gcloud.services.enable(gcloud.services.API.ArtifactRegistryAPI);
  }
  Shell.exec(
    `gcloud run deploy stylize  --source . --region="${region}" --project="${projectId}" --format=json`
  );
})();
