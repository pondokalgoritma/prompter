<div  x-show="isTeleprompterOpen" @click.away="isTeleprompterOpen = false" class="fixed inset-0 bg-gray-900 bg-opacity-90 flex justify-center items-center">
    <div class="bg-gray-800 rounded-md shadow-md w-full max-w-4xl space-y-4 overflow-hidden" style="height: 550px;">
        <div class="flex items-center justify-between border-b border-gray-700 rounded-t-md px-6 py-3 font-bold text-white">
            <h3 x-text="teleprompterRecord.studio + ' Etalase ' + teleprompterRecord.showcase + ' - ' + teleprompterRecord.title"></h3>
            <button @click="isTeleprompterOpen = false; stopScrolling();" class="hover:text-red-500">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="pl-6 pr-3">
            <div class="container overflow-y-auto pr-6" style="height: 460px;" x-ref="teleprompterContent">
                <div class="teleprompterContent text-3xl text-center space-y-4" @mouseenter="pausedOnHover" @mouseleave="scrollingOnLeave">
                    <div class="h-48"></div>
                    <div class="paragraph text-3xl uppercase font-bold rounded py-6 bg-red-700 text-yellow paragraph" x-html="teleprompterRecord.title"></div>
                    <template x-for="paragraph in paragraphs">
                        <div class="paragraph p-6 bg-gray-900 rounded" x-html="paragraph"></div>
                    </template>
                    <div class="h-96"></div>
                </div>
            </div>
        </div>
    </div>
</div>
