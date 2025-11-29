<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Responses } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { onMounted, ref } from 'vue';
import { Heading6 } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Отзывы',
        href: Responses().url,
    },
];

const props = defineProps<{ businessId: String, reviewsData: object }>()

//const currentIframeSrc = ref("https://yandex.ru/maps-reviews-widget/" + props.businessId + "?comments");

const stars = ref((props.reviewsData as any)?.starsDom?.stars);
const is_last_half = ref((props.reviewsData as any)?.starsDom?.is_last_half);
const empties = 5 - stars.value;
const comments = ref((props.reviewsData as any)?.comments);

</script>

<template>
    <Head title="Отзывы" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >

<div class="container">
  <div class="row">
    <div class="col-3-of-4">
      <div class="main-container">
        <div class="main">

                    <div class="badge__comments">
                        <div class="card comment" v-for="comment in comments">
                            <div class="comment__header flex">
                                <div class="comment__date-n-filial">
                                    <p class="comment__date-n-filial">{{ comment.date }} Филиал 1</p>
                                </div>
                                <div class="comment__stars">
                                    <ul class="stars-list">
                                        <li class="stars-list__star" v-for="index in comment.stars"></li>
                                        <li class="stars-list__star _empty" v-for="index in (5 - comment.stars)"></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="comment__subheader">
                                <p class="comment__date">{{ comment.name }} +7 900 540 40 40</p>
                            </div>
                            <p class="comment__text">{{ comment.text }}</p>
                        </div>
                    </div>


        </div>
      </div>
    </div>
    <div class="col-1-of-4">
      <div class="full-height-div">

                    <div class="card">
                        <div class="mini-badge__rating-info">
                            <p class="mini-badge__stars-count">{{ (props.reviewsData as any)?.rating }}</p>
                            <div>
                                <div class="mini-badge__stars">
                                    <ul class="stars-list">
                                        <li class="stars-list__star" v-for="index in stars" :class="is_last_half && index == stars ? '_half' : ''"></li>
                                        <li class="stars-list__star _empty" v-for="index in empties"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a class="mini-badge__rating" target="_blank" href="#">Всего отзывов: {{ (props.reviewsData as any)?.ratingMini }}</a>
                    </div>

      </div>
    </div>
  </div>
</div>

            <!--div style="display: none; width:760px;height:1800px;overflow:hidden;position:relative;">
                <iframe style="width:100%;height:100%;border:1px solid #e6e6e6;border-radius:8px;box-sizing:border-box"
                    :src="currentIframeSrc"></iframe>
            </div-->
        </div>
    </AppLayout>
</template>

<style scoped>

.mini-badge__rating-info {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-align: center;
  -webkit-align-items: center;
  align-items: center;
  margin-bottom: 16px;
}

.mini-badge__stars-count {
  font-size: 48px;
  margin-right: 12px;
  line-height: 56px;
}

.mini-badge__stars {
  width: 88px;
}

.mini-badge__stars {
  margin-bottom: 8px;
}

.stars-list {
  display: -webkit-box;
  display: -webkit-flex;
  display: flex;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  justify-content: space-between;
  width: 88px;
}

.stars-list__star {
  background: url(/storage/images/star.svg) no-repeat 50%/cover;
}

.stars-list__star._half {
  background-image: url(/storage/images/halfstar.svg);
}

.stars-list__star._empty {
  background-image: url(/storage/images/emptystar.svg);
}

.stars-list__star {
  width: 16px;
  height: 16px;
}

* {
  font-family: YS Text,sans-serif;
  margin: 0;
  margin-right: 0px;
  padding: 0;
  text-decoration: none;
  list-style: none;
}

.badge, .mini-badge, .mini-badge__org-name {
  color: #000;
}

.card {
    color: #363740;
    font-size: 12px;
    padding-left: 18px;
    padding-right: 25px;
    padding-bottom: 30px;
    border: 1px solid #E0E7EC;
    width: fit-content;
    margin-left: 21px;
    margin-bottom: 20px;
}


.container {
  border: 1px dotted white;
}

.row {
  background: white;
  display: flex;
}

.col-3-of-4 {
  width: 75%;
  flex-grow: 0;
}

.col-1-of-4 {
   flex-grow: 1;
   margin-left: 10px;
}

.main-container {
  height: 0;
  overflow: scroll;
  padding-top: 75%;
  position: relative;
  width: 100%;
}

.main {
  background: white;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  text-align: left;
}

.full-height-div {
  height: 100%;
  border: 1px dotted #fff;
}

.comment__date-n-filial, .comment__subheader {
    font-weight: bold;
}

.comment__header {
    justify-content: space-between;
    margin-top: 19px;
}

</style>
