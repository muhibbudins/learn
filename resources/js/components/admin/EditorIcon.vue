<template>
  <div
    class="icon"
    :class="[`icon--${name}`, `icon--${size}`, { 'has-align-fix': fixAlign }]"
  >
    <svg class="icon__svg">
      <image
        :xlink:href="'/icons/' + name + '.svg'"
        x="0"
        y="0"
        height="12px"
        width="12px"
      />
    </svg>
  </div>
</template>

<script>
export default {
  props: {
    name: {},
    size: {
      default: "normal"
    },
    modifier: {
      default: null
    },
    fixAlign: {
      default: true
    }
  }
};
</script>

<style lang="scss" scoped>
.icon {
  position: relative;
  display: inline-block;
  vertical-align: middle;
  width: 1rem;
  height: 1rem;
  margin: 0 0.3rem;
  top: 0.3rem;
  fill: currentColor;
  margin-bottom: 5px;

  &__svg {
    display: inline-block;
    vertical-align: top;
    width: 100%;
    height: 100%;
  }
  &:first-child {
    margin-left: 0;
  }
  &:last-child {
    margin-right: 0;
  }
}
// svg sprite
body > svg,
.icon use > svg,
symbol {
  path,
  rect,
  circle,
  g {
    fill: currentColor;
    stroke: none;
  }
  *[d="M0 0h24v24H0z"] {
    display: none;
  }
}
</style>
