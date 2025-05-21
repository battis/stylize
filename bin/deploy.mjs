import gcloud from '@battis/partly-gcloudy';
import { Core } from '@battis/qui-cli.core';

(async () => {
  await Core.init();
  await gcloud.batch.appEngineDeployAndCleanup({ retainVersions: 2 });
})();
